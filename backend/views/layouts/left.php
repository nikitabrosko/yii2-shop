<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user_icon.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Users', 'icon' => 'user', 'url' => ['/user/index'], 'active' => $this->context->id == 'user/index'],

                    ['label' => 'Shop', 'icon' => 'folder', 'items' => [
                        ['label' => 'Brands', 'icon' => 'file-o', 'url' => ['shop/brand/index'], 'active' => $this->context->id == 'shop/brand/index'],
                        ['label' => 'Tags', 'icon' => 'file-o', 'url' => ['shop/tag/index'], 'active' => $this->context->id == 'shop/tag/index'],
                        ['label' => 'Categories', 'icon' => 'file-o', 'url' => ['shop/category/index'], 'active' => $this->context->id == 'shop/category/index'],
                        ['label' => 'Characteristics', 'icon' => 'file-o', 'url' => ['shop/characteristic/index'], 'active' => $this->context->id == 'shop/characteristic/index'],
                        ['label' => 'Products', 'icon' => 'file-o', 'url' => ['shop/product/index'], 'active' => $this->context->id == 'shop/product/index'],
                        ['label' => 'Delivery methods', 'icon' => 'file-o', 'url' => ['shop/delivery/index'], 'active' => $this->context->id == 'shop/delivery/index'],
                        ['label' => 'Orders', 'icon' => 'file-o', 'url' => ['shop/order/index'], 'active' => $this->context->id == 'shop/order/index'],
                        ['label' => 'Discounts', 'icon' => 'file-o', 'url' => ['shop/discount/index'], 'active' => $this->context->id == 'shop/discount/index'],
                    ]],
                    ['label' => 'Blog', 'icon' => 'folder', 'items' => [
                        ['label' => 'Tags', 'icon' => 'file-o', 'url' => ['blog/tag/index'], 'active' => $this->context->id == 'blog/tag/index'],
                        ['label' => 'Category', 'icon' => 'file-o', 'url' => ['blog/category/index'], 'active' => $this->context->id == 'blog/category/index'],
                    ]],
                ],
            ]
        ) ?>

    </section>

</aside>
