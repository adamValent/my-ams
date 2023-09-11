<?php

declare(strict_types=1);

namespace App\CoreModule\Presenters;

use App\Presenters\BasePresenter;
use App\CoreModule\Model\ArticleManager;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Article render presenter.
 * @package App\CoreModule\Presenters
 */
class ArticlePresenter extends BasePresenter
{
    /**
     * Constructor with default article URL setting and dependency injection of article management model.
     * @param string $defaultArticleUrl Default article URL.
     * @param ArticleManager $articleManager Injected article management model.
     */
    public function __construct(private string         $defaultArticleUrl,
                                private ArticleManager $articleManager)
    {
        parent::__construct();
    }

    /**
     * Retrieves and passes an article to the template.
     * @param string|null $url article URL.
     * @return void
     * @throws BadRequestException If article with given URL was not found.
     */
    public function renderDefault(string $url = null): void
    {
        if (!$url) $url = $this->defaultArticleUrl;

        /** Attempts to retrieve an article with given URL. Throws 404 error on failure. */
        if (!($article = $this->articleManager->getArticle($url))) {
            $this->error('Article cannot be found');
        }

        // pass $article variable to the template (Article/default.latte)
        $this->template->article = $article;
    }

    /**
     * Retrieves and passes a list of articles to the template.
     * @return void
     */
    public function renderList(): void
    {
        // pass $articles variable to the template (Article/list.latte)
        $this->template->articles = $this->articleManager->getArticles();
    }

    /**
     * Removes an article.
     * @param string|null $url Article URL.
     * @return void
     * @throws AbortException
     */
    public function actionRemove(string $url = null): void
    {
        $this->articleManager->removeArticle($url);
        $this->flashMessage('Article was removed successfully.');
        $this->redirect('Article:list');
    }

    /**
     * Renders the form for article editing.
     * @param string|null $url Article URL address.
     * @return void
     */
    public function actionEditor(string $url = null): void
    {
        if ($url) {
            if (!($article = $this->articleManager->getArticle($url))) {
                $this->flashMessage('Article cannot be found.');
            } else {
                $this->getComponent('editorForm')->setDefaults($article);
            }
        }
    }

    /**
     * Creates an editor form.
     * @return Form Editor form.
     */
    protected function createComponentEditorForm(): Form
    {
        $form = new Form;
        $form->addHidden('article_id');
        $form->addText('title', 'Title')->setRequired();
        $form->addText('url', 'URL')->setRequired();
        $form->addText('description', 'Description')->setRequired();
        $form->addTextArea('content', 'Content');
        $form->addSubmit('send', 'Submit article');
        $form->onSuccess[] = $this->editorFormSucceeded(...);
        return $form;
    }

    /**
     * Executes after successful submit od editor form.
     * @param ArrayHash $data Data passed via submitted editor form.
     * @return void
     * @throws AbortException
     */
    private function editorFormSucceeded(ArrayHash $data): void
    {
        try {
            $this->articleManager->saveArticle($data);
            $this->flashMessage('Article was submitted successfully.');
            $this->redirect('Article:', $data->url);
        } catch (UniqueConstraintViolationException $ex) {
            $this->flashMessage('Article with given URL is already present.');
        }
    }
}