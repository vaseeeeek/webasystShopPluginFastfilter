$(document).ready(function() {
    $('.js-delete-setting').click(function() {
        console.log(123);
        var settingId = $(this).data('id');
        if (confirm('Вы уверены, что хотите удалить эту запись?')) {
            $.ajax({
                url: '?module=plugins&plugin=fastfilter&action=delete',
                type: 'POST',
                dataType: 'json',
                data: { id: settingId },
                success: function(response) {
                    if (response.status === 'ok') {
                        alert(response.message);
                        location.reload(); 
                    } else {
                        alert('Ошибка: ' + response.errors.join(', '));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }
    });
});
