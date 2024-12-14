<!-- ========== title-wrapper start ========== -->
<?php $customer_id = session('client_cust_id') ?>
<?php $contact_id = session('contact_id') ?>
<div class="title-wrapper pt-30">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="title">
                <h2>Knowledge Base</h2>
            </div>
        </div>
        <!-- end col -->
        <div class="col-md-6">
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="<?= url_to("front.Knowledgebase.view") ?>">Knowledge Base</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<?//=  var_dump(get_client_permission()) ?>
<?//=  var_dump(has_client_permission("Invoices")) ?>
<div class="kb-container">
    <form action="<?= url_to('front.knowledgebase.search') ?>" method="POST">
        <div class="w-100">
            <h2 class="text-center">Search Knowledge Base Articles</h2>
            <div class="search-container">
                <div class="input-group">
                    <?php $search = isset($searchText) ? $searchText : "" ?>
                    <input type="search" class="search-input" id="search" name="search" placeholder="Have a question ?"
                        value="<?= $search ?>">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success kb-search-button">Search</button>
                    </span>
                    <i class="fa-solid fa-magnifying-glass fa-lg search-icon" style="color: #808080;"></i>
                </div>
            </div>
        </div>
    </form>
    <div class="kb-result-container" id="result-container">
        <?php if (isset($knowledgebase_article)) { ?>
            <?php //var_dump($knowledgebase_article) ?>
            <?php if (count($knowledgebase_article) > 0 && $knowledgebase_article != "") { ?>
                <?php foreach ($knowledgebase_article as $key => $value) { ?>
                    <div class="kb-result-child">
                        <div class="d-flex justify-content-between align-items-center">

                            <h5><?= $value["article_subject"] ?></h5>
                            <p class="date-time-kb"><?= $value["date_added"] ?></p>
                        </div>
                        <div class="article-description text-justify">
                            <?= $value["article_description"] ?>
                            <hr>
                            <div class="foot-container p-2">
                                <?php if (isset($value["id"])) { ?>
                                    <?php if ($value["customer_id"] == $contact_id) { ?>
                                        <p>Thanks for Your Feedback!</p>
                                        <div class="voted-container">
                                            <i class="fa-solid fa-circle-check" style="color: #177c03;"></i> Voted
                                        </div>
                                    <?php } else { ?>
                                        <p>Did you find this article useful?</p>
                                        <div>
                                            <button type="button" class="feedback btn bg-success text-white"
                                                data-article_id="<?= $value['article_id'] ?>" data-type="positive">Yes</button>
                                            <button type="button" class="feedback btn bg-danger text-white"
                                                data-article_id="<?= $value['article_id'] ?>" data-type="negative">No</button>

                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>Did you find this article useful?</p>
                                    <div>
                                        <button type="button" class="feedback btn bg-success text-white"
                                            data-article_id="<?= $value['article_id'] ?>" data-type="positive">Yes</button>
                                        <button type="button" class="feedback btn bg-danger text-white"
                                            data-article_id="<?= $value['article_id'] ?>" data-type="negative">No</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <span class="arrow" id="extend_it"><i class="fa-solid fa-arrow-down"></i></span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="kb-result-child">
                    <p class="text-center">No knowledge base articles were found</p>
                </div>
            <?php } ?>

        <?php } ?>
    </div>
</div>
<script src="<?= base_url() . 'front-assets/' ?>js/custom.js"></script>
<script>
    $(".arrow").on("click", function (e) {
        let btn = $(e.target);
        let description = btn.closest(".kb-result-child").find(".article-description");
        let container = btn.closest(".kb-result-child");
        btn.toggleClass("rotate");
        description.toggleClass("show");

        let topheight_diff = 310;

        if (window.innerWidth <= 768) {
            topheight_diff = 310;
        }

        if (!description.hasClass("show")) {
            $("html, body").animate({
                scrollTop: $(container).offset().top - topheight_diff
            }, 500);
        }
    })

    $("#search").on("click", function () {
        console.log($(this.value));
        console.log("hit");
    })

    $(".feedback").on("click", (e) => {
        let data = $(e.target).data("type");
        let article_id = $(e.target).data("article_id");
        // console.log("article -> ", article_id);
        $(".feedback").prop("disabled", true);
        if (data) {
            $.ajax({
                url: "<?= url_to("front.submit.feedback") ?>",
                type: "post",
                data: {
                    type: data,
                    id: article_id,
                    contact_id: <?= session('contact_id') ?>
                },
                success: function (response) {
                    console.log(response);
                    var alert_content = new Alert();
                    if (response.success) {
                        alert_content.alert_invoke("success", response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500)
                    } else {
                        alert_content.alert_invoke("error", response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500)
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr)
                    console.log(status)
                    console.log(error)
                }
            })
        }
    })

    <?php if (session()->getFlashdata('success')) { ?>
        let alert = new Alert();
        alert.alert_invoke('success', '<?= session()->getFlashdata('success') ?>');
    <?php } elseif (session()->getFlashdata('error')) { ?>
        let alert = new Alert();
        alert.alert_invoke('error', '<?= session()->getFlashdata('error') ?>');
    <?php } ?>
</script>