<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class cis3integration_lib 
{
    public $bucket_name = "myigniter";
    public $region = 'us-west-2';
    public $version = 'latest';
    public $scheme = 'http';
    
    public $s3Client = null;
    
    public function __construct(array $config = array(), $reset = TRUE)
    {
        $reflection = new ReflectionClass($this);
        if ($reset === TRUE)
        {
                $defaults = $reflection->getDefaultProperties();
                foreach (array_keys($defaults) as $key)
                {
                        if ($key[0] === '_')
                        {
                                continue;
                        }

                        if (isset($config[$key]))
                        {
                                if ($reflection->hasMethod('set_'.$key))
                                {
                                        $this->{'set_'.$key}($config[$key]);
                                }
                                else
                                {
                                        $this->$key = $config[$key];
                                }
                        }
                        else
                        {
                                $this->$key = $defaults[$key];
                        }
                }
        }
        else
        {
                foreach ($config as $key => &$value)
                {
                        if ($key[0] !== '_' && $reflection->hasProperty($key))
                        {
                                if ($reflection->hasMethod('set_'.$key))
                                {
                                        $this->{'set_'.$key}($value);
                                }
                                else
                                {
                                        $this->$key = $value;
                                }
                        }
                }
        }

        //AWS account setting
        // define('AWS_ACCESS_KEY',"AKIAIHKYRRFSOPHIVPMQ");
        // define('AWS_SECRET_KEY',"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");
        define('AWS_ACCESS_KEY',"AKIAIU5YEJECBIXXFHXA");
        define('AWS_SECRET_KEY',"2bwpegR6jSrI6u/ovrRMfknUvoShCzyV+znwTCvh");

        define('BUCKET_NAME',$this->bucket_name);//The bucket name you want to use for your project
        define('AWS_URL','http://'.$this->bucket_name.'.s3.amazonaws.com/');

        /*define('AWS_ACCESS_KEY',"{{AWS_ACCESS_KEY}}");
        define('AWS_SECRET_KEY',"{{AWS_SECRET_KEY}}");

        define('BUCKET_NAME','{{BUCKET_NAME}}');//The bucket name you want to use for your project
        define('AWS_URL','https://'.BUCKET_NAME.'.s3.amazonaws.com/');
        */

        //check AWS access key is set or not
        if(trim(AWS_ACCESS_KEY,"{}")=="AWS_ACCESS_KEY")
        {
            exit("CI S3 Integration configuration error! Please input the AWS Access Key, "
                        . "AWS Secret Key and Bucket Name in applicatin/libraries/cis3integration_lib.php file");
        }
        require_once('awssdk3/aws-autoloader.php');	

        //Create S3 client
        $sharedConfig = [
            'region'  => $this->region,
            'version' => $this->version,
            'scheme' => $this->scheme,
            'credentials' => [
                'key'    => AWS_ACCESS_KEY,
                'secret' => AWS_SECRET_KEY,
            ],
        ];
        $sdk = new Aws\Sdk($sharedConfig);
        $this->s3Client = $sdk->createS3();                
    }

    /**
     * Delete S3 Object
     *
     * @access public
     */    	
    function delete_s3_object($file_path)
    {
            $response = $this->s3Client->deleteObject(array(
                'Bucket'     => $this->bucket_name,
                'Key'        => $file_path
            ));
            return true;
    }

    /**
     * Copy S3 Object
     *
     * @access public
     */ 
    function copy_s3_file($source,$destination)
    {
            $response = $this->s3Client->copyObject(array(
                'Bucket'     => $this->bucket_name,
                'Key'        => $destination,
                'CopySource' => "{$this->bucket_name}/{$source}",
            ));
            if($response['ObjectURL'])
            {
                return true;
            }
            return false;
    }

    /**
     * Create a new bucket in already specified region
     *
     * @access public
     */ 
    function create_bucket($bucket_name="",$region="")
    {
            $promise = $this->s3Client->createBucketAsync(['Bucket' => $bucket_name]);
            try {
                $result = $promise->wait();
                return true;
            } catch (Exception $e) {
                //echo "exception";exit;
                //echo $e->getMessage();
               return false;
            }		
    }
	
}