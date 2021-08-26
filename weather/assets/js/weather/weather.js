
import '../../css/weather/weather.css';
import $ from 'jquery';
import swal from 'sweetalert2';
window.Swal = swal;

$( document ).ready(function() {
    $('body').on( "click",".checkWeather", function() {
        getWeather($('.serviceType').val(),$('.cityWeather').val());
    }); 
});

function getWeather(serviceType,weatherContnerCity){
    
    $('.weatherContnerErrorMessage').text('');
    
    if(serviceType.length <= 0 || weatherContnerCity.length <= 0){
        $('.weatherContnerErrorMessage').text('Pola nie mogą być puste');
        return false;
    }

    swal.showLoading();
    
    $.ajax({ 
        url: '/index.php/ajax',
        type: 'post', 
        data: {
            serviceType: serviceType,
            city: weatherContnerCity,
        }, 
        dataType : 'json',
        success: function(response){
            $('.weatherContnerBody').html();
            $('.weatherContnerErrorMessage').text('');
            
            switch(response.status){
                case 'error':
                    $('.weatherContnerErrorMessage').text(response.message);
                break;
                case 'ok':
                    $('.weatherContnerBody').html(response.html);
                break;
            }
            swal.close();
        } 
    });

}