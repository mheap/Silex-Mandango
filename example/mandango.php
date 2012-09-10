<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application,
    SilexMandango\MandangoExtension;

$app = new Application();

$loader->add('Model', __DIR__ . '/odm');

$app['mandango.token']                = '4dd399ea814c';
$app['mandango.cache_dir']            = __DIR__ . '/odm/cache';
$app['mandango.default_connection']   = 'local';

$app['mandango.connections'] = array(
    'local' => array(
        'host'     => 'mongodb://localhost:27017',
        'database' => 'mandango'
    )
);

$app['mandango.configuration'] = array(
    'metadata_factory_class'    => 'Model\Mapping\Metadata',
    'metadata_factory_output'   => __DIR__ . '/odm/Model/Mapping',
    'default_output'            => __DIR__ . '/odm/Model',
    'schema_file'               => __DIR__ . '/odm/schema.php'
);

$app->register(new SilexMandango\MandangoExtension());


$app->get('/', function() use($app) {

    $amount = $app['mandango']
        ->getRepository('Model\Article')
        ->count();

    $article = $app['mandango']->create('Model\Article');
    $article->setTitle('Article #' . ($amount+1));
    $article->setContent('Lorem ipsum ...');
    $article->save();

    return 'Created Model\Article with ID: ' . $article->getId();
});

$app->run();