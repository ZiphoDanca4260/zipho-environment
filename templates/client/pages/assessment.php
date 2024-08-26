<style>
    .box {
        background-color: white;
        outline: 2px dashed black;
        height: 500px;
        width: 800px;
    }
    .box {
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- BANNER SECTION -->
<section class="boxcar-banner-section-v1 alt" style="min-height: 300px;">
    <div class="container">
        <div class="banner-content">
            <h2 class="wow fadeInUp p-0 m-0" data-wow-delay="100ms">Assessment</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->


<!-- ASSESSMENT -->
<section class="layout-section" style="min-height: 100dvh;">
    <div class="d-flex flex-column align-items-center p-3">
        <div data-fr-section="image-form" enctype="multipart/form-data">
            <div class="box">
                <label>
                    <strong>Choose files</strong>
                    <span>or drag them here.</span>
                    <input class="box__file" type="file" id="files" accept="image/png, image/gif, image/jpeg" data-fr-name0="files" name="files[]" multiple />
                </label>
                <br/>
                <img id="preview" src="#" alt="Preview" width="150px"/>
            </div>
            <button type="button" class="btn btn-success mt-4" data-fr-action="submit">Submit</button>
        </div>

        <h1>JS</h1>
        <h3>Here will be a text editor for the admin to create blog posts.</h3>
        <p>The admin should be able to use simple markup and also they should be able to add multiple pictures (average 15)</p>
        <p>The pictures can fit inside a carousel and a lightbox or they can be grid. The editor doesnt have to be responsive but the output/preview is required to be responsive.</p>
        <h1>PHP</h1>
        <p>files should be sent to the endpoint 'https://(server)/assessment-api'</p>
        <p>Files should be saved to a new table on the database 'usr_blog_posts'</p>
        <p>the global $db object should be used to save the entry to the database</p>

        <button type="button" class="btn btn-danger" data-fr-action="test-ajax">Test Ajax</button>
    </div>
</section>
