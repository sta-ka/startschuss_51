<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Eloquent\Misc\Article\ArticleRepositoryInterface as Articles;
use App\Services\Creator\ArticleCreator;

use App\Http\Requests\Article\NewArticleRequest;
use App\Http\Requests\Article\UpdateDataRequest;

use Input;
use Notification;

/**
 * Class ArticlesController
 *
 * @package App\Http\Controllers\Admin
 */
class ArticlesController extends Controller {

    /**
     * @var Articles
     */
    private $articleRepo;

	/**
	 * Constructor: inject dependencies
     *
     * @param Articles $articleRepo
	 */
	public function __construct(Articles $articleRepo)
	{
		$this->articleRepo = $articleRepo;
	}

	/**
	 * Articles overview
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		$data['articles'] = $this->articleRepo->getAll();

		return view('admin.articles.show', $data);
	}

	/**
	 * Display 'New article' form
     *
     * @return \Illuminate\View\View
	 */
	public function compose()
	{
		return view('admin.articles.new');
	}

	/**
	 * Create new article
     *
     * @param NewArticleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function create(NewArticleRequest $request)
	{
		$article = new ArticleCreator($this->articleRepo);
		$success = $article->createArticle(Input::all());

		$success ?
			Notification::success('article_created_successful') :
			Notification::error('article_created_unsuccessful');

		return redirect('admin/articles');
	}

	/**
	 * Display 'Edit article' form
     *
     * @param int $article_id
     *
     * @return \Illuminate\View\View
	 */
	public function edit($article_id)
	{
		$data['article'] = $this->articleRepo->findById($article_id);

		return view('admin.articles.edit', $data);
	}

	/**
	 * Process editing article
     *
     * @param UpdateDataRequest $request
     * @param int $article_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateDataRequest $request, $article_id)
	{
		$article = new ArticleCreator($this->articleRepo);
		$article->edit(Input::all(), $article_id);

		return redirect('admin/articles/'.$article_id.'/edit');
	}

	/**
	 * Delete image
     *
     * @param int $article_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteImage($article_id)
	{
		$article = $this->articleRepo->findById($article_id);

        $filename = $article->image;

		if (empty($filename)) {
            Notification::error('image_deleted_unsuccessful');
            return redirect('admin/articles/' . $article_id . '/edit');
        }

        // delete image in the database
        $article->update(['image' => null]);

        // delete files
        \File::delete('uploads/images/original/'.$filename);
        \File::delete('uploads/images/small/'.$filename);

        Notification::success('image_deleted_successful');
        return redirect('admin/articles/'.$article_id.'/edit');

    }

	/**
	 * Delete article
     *
     * @param int $article_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($article_id)
	{
		$article = $this->articleRepo->findById($article_id);

		$article->delete() ? 
			Notification::success('article_delete_successful') :
			Notification::error('article_delete_unsuccessful');
		
		return redirect('admin/articles');
	}

}