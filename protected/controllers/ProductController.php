<?php
/**
 * Description of ProductController
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
class ProductController extends Controller{

  public function actionIndex($id) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.catalog.models.Category');

    $product = Product::model()->with('brand')->findByPk($id, 'show_me=1');
    if (is_null($product))
      throw new CHttpException(404, "Товар недоступен");
    $search = new Search;
    $productForm = new ProductForm;

    if ($product->seo)
      Yii::app()->clientScript->registerMetaTag($product->seo, 'description');
      
    if (isset($_POST['ProductForm'])) {
      $this->addToCart($_GET['id'], $_POST['ProductForm']['quantity']);
      $this->redirect('/cart');
    }

    $params = array(
      'search' => $search,
      'product' => $product,
      'productForm' => $productForm,
    );
//    if (isset($_POST['currentPage']))
//      $params['page'] = $_POST['currentPage'];
    if (isset($_GET['back']))
      $params['url'] = $_GET['back'];

    $this->render('product', $params);
  }

}
