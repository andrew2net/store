<?php
/**
 * Description of ProductController
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
class ProductController extends CController{

  public function actionIndex($id) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.catalog.models.Category');

    $product = Product::model()->with('brand')->findByPk($id);
    $search = new Search;
    $productForm = new ProductForm;

    if (isset($_POST['ProductForm'])) {
      $this->addToCart($_GET['id'], $_POST['ProductForm']['quantity']);
      $this->redirect('/cart');
    }

    $params = array(
      'search' => $search,
      'product' => $product,
      'productForm' => $productForm,
    );
    if (isset($_POST['currentPage']))
      $params['page'] = $_POST['currentPage'];
    if (isset($_POST['url']))
      $params['url'] = $_POST['url'];

    $this->render('product', $params);
  }

}
