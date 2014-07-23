<?php namespace Atlantis\Page\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent;
use Lavalite\Filer\FilerTrait;
use Dimsav\Translatable\Translatable;


class Page extends Model  {

    use Translatable;
    use FilerTrait;

    protected $table        = 'pages';

    protected $module       = 'page';

    protected $softDelete   = true;


    public $autoHydrateEntityFromInput      = true;
    public $forceEntityHydrationFromInput   = true;

    public static $rules = array(
        //'name'      => 'required'
    );

    /**
     * Array with the fields translated in the Translation table
     *
     * @var array
     */
    public $translatedAttributes = array('heading','content','title',
        'keyword','description','image');

    /**
     * Set $translationModel if you want to overwrite the convention
     * for the name of the translation Model. Use full namespace if applied.
     *
     * The convention is to add "Translation" to the name of the class extending Translatable.
     * Example: Country => CountryTranslation
     */
    public $translationModel = 'Lavalite\Page\Models\PageLang';

    /**
     * This is the foreign key used to define the translation relationship.
     * Set this if you want to overwrite the laravel default for foreign keys.
     *
     * @var
     */
    public $translationForeignKey   = 'page_id';

    /**
     * Add your translated attributes here if you want
     * fill them with mass assignment
     *
     * @var array
     */
    protected $fillable = ['name','slug','order','status', 'heading',
        'content','title','keyword','description','abstract','image'];

    /**
     * The database field being used to define the locale parameter in the translation model.
     * Defaults to 'locale'
     *
     * @var string
     */
    public $localeKey = 'lang';

    protected $keepRevisionOf = array(
        'name', 'content','title','keyword','description','abstract');

    protected $uploads          = array(
        'single' 	=> array('banner', 'image')
    );

    public function setNameAttribute($name)
    {
        $this -> attributes['name'] 	= $name;

        if (trim($this -> getAttribute('title')) == '')
            $this -> setAttribute('title', $name);

        if (trim($this -> getAttribute('heading')) == '')
            $this -> setAttribute('heading', $name);
    }

    /**
     * returns the current package
     *
     * @return string
     */
    private function getPackage()
    {
        return Config::get('page::package');
    }

    public function getBannerAttribute($banner)
    {
        if ($banner != '') return $banner;
        return Config::get('page::banner');
    }
    /**
     * Listen for save and updating event
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            if(!empty($model->id)) $model->upload();
            $model->slug = !empty($model->slug) ? $model->slug : $model->getUniqueSlug($model->name);
            return $model->validate();
        });

    }
}