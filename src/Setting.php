<?php 
 
namespace LevooLabs\Settings; 

use Cache;
use Illuminate\Support\Collection;
use Illuminate\Cache\CacheManager;
use Illuminate\Encryption\Encrypter;
use LevooLabs\Settings\Models\Setting as SettingModel;

/**
 * Setting Library
 * Offers basic setting management using the database and cache
 */
class Setting
{

    private $cache;
    private $encrypter;

    public function __construct(CacheManager $cache, Encrypter $encrypter) {
        $this->cache = $cache;
        $this->encrypter = $encrypter;
    }

    /**
     * Returns wether the given setting exists or not
     *
     * @param  string $name Setting name
     * @return boolean
     */
    public function exists($name) {
        $result = $this->get($name);
        return $result !== null && !empty($result);
    }

    /**
     * Returns the setting value represented by the given name
     *
     * @param  string $name Setting name
     * @return string
     */
    public function get($name) 
    {
        $result = $this->cache->get($name, function () use ($name) {
            $setting = SettingModel::where('name', $name)->first();
            if ($setting === null) {
                return null;
            }
            $this->cache->forever($setting->name, $setting->value);
            return $setting->value;
        });
        return $result;
    }

    /**
     * Updates the given setting with the given value
     *
     * @param  string $name Setting name
     * @param  string $value Setting value
     * @return void
     */
    public function set($name, $value)
    {
        SettingModel::updateOrCreate(['name' => $name], ['value' => $value]);
        $this->cache->forever($name, $value);
    }

    /**
     * Gets or sets the boolean value represented by the given name
     *
     * @param  string $name Setting name
     * @param  mixed $value Setting value
     * @return mixed
     */
    public function bool($name, $value = null)
    {
        if ($value === null) {
            return filter_var($this->get($name), FILTER_VALIDATE_BOOLEAN);   
        }
        $this->set($name, filter_var($value, FILTER_VALIDATE_BOOLEAN));
    }

    /**
     * Gets or sets the integer value represented by the given name
     *
     * @param  string $name Setting name
     * @param  mixed $value Setting value
     * @return mixed
     */
    public function integer($name, $value = null)
    {
        if ($value === null) {
            $result = $this->get($name);
            return $result !== null ? (int)$result : 0;
        }
        $this->set($name, (int)$value);
    }

    /**
     * Gets or sets the collection represented by the given name
     *
     * @param  string $name Setting name
     * @param  array|Collection $array Setting array or collection
     * @return mixed
     */
    public function collection($name, $array = null)
    {
        if ($array === null) {
            $result = $this->get($name);
            return $result !== null ? new Collection(explode(';', $result)) : new Collection([]);
        }
        $value = is_array($array) ? implode(';', $array) : $array->implode(';');
        $this->set($name, $value);
    }

    /**
     * Gets or sets the json value represented by the given name
     *
     * @param  string $name Setting name
     * @param  array $json Setting json array
     * @return mixed
     */
    public function json($name, $json = null)
    {
        if ($json === null) {
            return json_decode($this->get($name));
        }
        $this->set($name, json_encode($json));
    }

    /**
     * Gets or sets the encrypted/decrypted text value represented by the given name 
     *
     * @param  string $name Setting name
     * @param  string $value Setting value
     * @return mixed
     */
    public function secret($name, $value = null)
    {
        if ($value === null) {
            return $this->encrypter->decryptString($this->get($name));
        }
        $this->set($name, $this->encrypter->encryptString($value));
    }
    
}
