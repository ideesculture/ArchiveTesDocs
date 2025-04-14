$(window).on('load', function()
{
    centerContent();
});

$(window).on('resize', function()
{
    centerContent();
});

function centerContent()
{
    var container = $('.navbar-header');
    var content = $('.img-responsive');
    $('.img-responsive').css("margin-left", (container.width()-content.width())/2);
    $('.img-responsive').css("margin-top", (container.height()-content.height())/2);
}