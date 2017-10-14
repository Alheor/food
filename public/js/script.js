$( document ).ready(function() {
    $('.category-list span').on('click', function () {
        $(this.parentNode).find('ul').first().toggle("slow");
    });
});
