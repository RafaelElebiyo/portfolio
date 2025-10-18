<div class="projects-filters mb-5">
    <div class="d-flex flex-wrap justify-content-center gap-2">
        <button class="btn btn-outline-light filter-btn active" data-filter="all"><?= t('projects.filters.all') ?></button>
        <button class="btn btn-outline-light filter-btn" data-filter="web"><?= t('projects.filters.web') ?></button>
        <button class="btn btn-outline-light filter-btn" data-filter="mobile"><?= t('projects.filters.mobile') ?></button>
        <button class="btn btn-outline-light filter-btn" data-filter="cross-platform"><?= t('projects.filters.cross_platform') ?></button>
        <button class="btn btn-outline-light filter-btn" data-filter="cms"><?= t('projects.filters.cms') ?></button>
        <button class="btn btn-outline-light filter-btn" data-filter="cloud"><?= t('projects.filters.cloud') ?></button>
    </div>
    
    <div class="mt-3 text-center">
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="sort" id="sort-date" autocomplete="off" checked>
            <label class="btn btn-outline-primary" for="sort-date"><?= t('projects.filters.sort_date') ?></label>
            
            <input type="radio" class="btn-check" name="sort" id="sort-popular" autocomplete="off">
            <label class="btn btn-outline-primary" for="sort-popular"><?= t('projects.filters.sort_popular') ?></label>
        </div>
    </div>
</div>