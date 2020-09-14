<!-- Добавление поста -->
<main class="page__main page__main--adding-post">
      <div class="page__main-section">
        <div class="container">
          <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
          <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
              <ul class="adding-post__tabs-list filters__list tabs__list">
                <?php foreach ($content_types as $post_type => $content_type): ?>
                <li class="adding-post__tabs-item filters__item">
                  <a class="adding-post__tabs-link filters__button filters__button--<?=$content_type['icon_name']; ?> <?php if ($form_type == $content_type['icon_name']):?> filters__button--active  tabs__item--active filters__button--active<?php endif; ?>" href="#">
                    <svg class="filters__icon" width="22" height="18">
                      <use xlink:href="#icon-filter-<?=$content_type['icon_name']; ?>"></use>
                    </svg>
                    <span><?=$content_type['content_name']; ?></span>
                  </a>
                </li>
              </ul>
              <?php endforeach ?>
            </div>
            <div class="adding-post__tab-content">
              <?php foreach ($content_types as $post_type => $content_type) {
                  $form = include_template('../templates/post/'.$content_type['icon_name'].'-form.php',['form_type' => $form_type]);
                  print($form);
              } ?>




            </div>
          </div>
        </div>
      </div>
    </main>
