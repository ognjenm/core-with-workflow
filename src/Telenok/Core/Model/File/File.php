<?php

namespace Telenok\Core\Model\File;

class File extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'file';
	protected $ruleList = ['title' => ['required', 'min:1']];
 
	public function isImage()
	{
		if ($this->exists && in_array($this->uploadFileFileMimeType->mime_type, [
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
			'image/tiff',
		]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


    public function category()
    {
        return $this->belongsToMany('\Telenok\File\FileCategory', 'pivot_relation_m2m_category_file', 'category_file', 'category')->withTimestamps();
    }


    public function uploadFileFileExtension()
    {
        return $this->belongsTo('\Telenok\File\FileExtension', 'upload_file_file_extension');
    }


    public function uploadFileFileMimeType()
    {
        return $this->belongsTo('\Telenok\File\FileMimeType', 'upload_file_file_mime_type');
    }


    public function categoryFileShopCategory()
    {
        return $this->belongsToMany('\ShopCategory', 'pivot_relation_m2m_category_file_shop_category', 'category_file', 'category_file_shop_category')->withTimestamps();
    }


    public function productFileShopProduct()
    {
        return $this->belongsToMany('\ShopProduct', 'pivot_relation_m2m_product_file_shop_product', 'product_file', 'product_file_shop_product')->withTimestamps();
    }


    public function manufacturerFileManufacturer()
    {
        return $this->belongsToMany('\Manufacturer', 'pivot_relation_m2m_manufacturer_file_manufacturer', 'manufacturer_file', 'manufacturer_file_manufacturer')->withTimestamps();
    }




    public function newsFileNews()
    {
        return $this->belongsToMany('\News', 'pivot_relation_m2m_news_file_news', 'news_file', 'news_file_news')->withTimestamps();
    }

}
?>