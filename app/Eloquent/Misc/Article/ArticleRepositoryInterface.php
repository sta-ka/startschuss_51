<?php namespace App\Eloquent\Misc\Article;

interface ArticleRepositoryInterface {

    /**
     * Create new article
     *
     * @param array $data
     *
     * @return static
     */
    public function create(array $data);

    /**
     * Get all articles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     * Get featured and active articles
     *
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeatured($limit);

    /**
     * Get other articles
     * if slug is given get 3 other articles
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOthers($slug = '');

    /**
     * Return count of article by slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function exists($slug);

    /**
     * Get article by Slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBySlug($slug);

    /**
     * Get article by ID
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findById($id);

    /**
     * Get last modified article in database
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLastModifiedArticle();

}