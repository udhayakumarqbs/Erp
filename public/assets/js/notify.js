$(document).ready(function () {




    getNotifications();

    setInterval(getNotifications, 10000);


    function getNotifications() {

        var data_url = $('#push_notifier').data('notify-url')
        $.ajax({
            url: data_url,
            method: 'GET',
            dataType: 'json',
            success: function(response){
                // result = JSON.parse(response)
                // console.log(result)
                // appendNotification(result);
                appendNotification(response);
            }

        });

    }


    function appendNotification(notifications){


        $('#notificationContainer').html('');

        $.each(notifications, function(index, notification){
            var newNotification = $(
                `<li>
                    <a href="#">
                        <h4 class="textFlow font-weight-bold">${notification.title}</h4>
                        <p class="textFlow">${notification.created_by}: sent you a message at ${notification.created_at}</p>
                    </a>
                </li>`
            );

            $('#notificationContainer').append(newNotification);

        });
    }
});
