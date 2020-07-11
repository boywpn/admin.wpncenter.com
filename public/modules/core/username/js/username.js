$(document).ready(function(){

    $('select[id=board_id]').on('select2:select', function (e) {
        let data = e.params.data;
        console.log(data);
        let text = data.text;
        text = text.split("_");
        console.log(text[1]);

        $(this).attr('data-prefix', text[1]);

        genUsername()
    });

    $("input[id=code]").keyup(function(){
        genUsername()
    })

})

function genUsername(){
    let prefix = $('select[id=board_id]').data('prefix');
    let code = $('input[id=code]').val();

    $('input[id=username]').val(prefix + code);
}