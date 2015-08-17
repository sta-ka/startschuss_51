<?php namespace App\Services\Creator;

use App\Eloquent\Misc\Article\ArticleRepositoryInterface as Articles;

class ArticleCreator {

	private $articleRepo;

    /**
     * @param Articles $articleRepo
     */
    public function __construct(Articles $articleRepo)
	{
		$this->articleRepo = $articleRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function createArticle($input)
	{
		$data = [
			'title'				=> $input['title'],
			'slug'				=> $input['slug'],
			'body'				=> \Purifier::clean($input['body']),
			'active'			=> \Input::get('active', false),
			'featured'			=> \Input::get('featured', false),
			'meta_description'	=> $input['meta_description'],
			'keywords'			=> $input['keywords']
		];

		if (\Input::hasFile('image'))
		{
			$filename = $this->uploadImage();

			$data['image'] = $filename;
		}

		// creates a new article in the article table
		return $this->articleRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $article_id
     *
     * @return bool|int
     */
    public function edit($input, $article_id)
	{
		$article = $this->articleRepo->findById($article_id);

		$data = [
			'title'				=> $input['title'],
			'slug'				=> $input['slug'],
			'body'				=> \Purifier::clean($input['body']),
			'active'			=> \Input::get('active', false),
			'featured'			=> \Input::get('featured', false),
			'meta_description'	=> $input['meta_description'],
			'keywords'			=> $input['keywords']
		];

		if (\Input::hasFile('image'))
		{
			$filename = $this->uploadImage();

			$data['image'] = $filename;
		}

		return $article->update($data);
	}

    /**
     * Perform update
     *
     * @return bool|int
     */
    protected function uploadImage()
	{
		// create random filename
		$filename = \Str::random(20) .'.'. \File::extension(\Input::file('image')->getClientOriginalName());
		$filename = \Str::lower($filename);

		// resize image and save it 
		\Image::make(\Input::file('image')->getRealPath())->resize(120, 120, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/images/small/'.$filename);

		// move uploaded file to public/uploads
		\Input::file('image')->move('uploads/images/original/', $filename);

		return $filename;
	}
}