jQuery(document).ready(function($) {
    /** Özel endpoint'e AJAX isteği yap **/
    /** Tüm Kullanıcı Listesini showAllUsers fonksiyonuna belirledim **/
    showAllUsers();
    function showAllUsers(){
        $.ajax({
            url: ajax.url,
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'custom_endpoint_callback',
            },
            success: function(response) {
                if (response.status === 'success') {
                    /** Verileri kullanarak HTML tablosunu oluşturuyoruz **/
                    var tableHtml = '<table>';
                    tableHtml += '<tr><th>ID</th><th>Ad</th><th>Kullanıcı Adı</th><th>İşlemler</th></tr>';
                    $.each(response.data, function(index, user) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + user.id + '</td>';
                        tableHtml += '<td>' + user.name + '</td>';
                        tableHtml += '<td>' + user.username + '</td>';
                        tableHtml += '<td><a href="#" class="user-details-link" data-user-id="' + user.id + '">Detaylar</a></td>';
                        tableHtml += '</tr>';
                    });
                    tableHtml += '</table>';
                    /** HTML tablosunu sayfaya ekliyoruz
                     *  (<div id="custom-table-container"></div>)
                     * **/
                    $('#custom-table-container').html(tableHtml);

                    /** Kullanıcı Detayına Tıklandığında ID Aktarıuoruz getUserDetails() **/
                    $('.user-details-link').on('click', function(event) {
                        event.preventDefault();
                        var userId = $(this).data('user-id');
                        getUserDetails(userId);
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX hatası: ' + textStatus, errorThrown);
            },
        });
    }

    /** Kullanıcı detaylarını getiren fonksiyon **/
    function getUserDetails(id) {
        $.ajax({
            url: ajax.url,
            type: 'GET',
            data: {
                action: 'get_user_details',
                id: id,
            },
            success: function(response) {
                if (response.status === 'success') {
                    /** Kullanıcı detaylarını göster **/
                    showUserDetails(response.data);
                } else {
                    console.error(response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX hatası: ' + textStatus, errorThrown);
            },
        });
    }

    /** Detay tablosunu Aktarma **/
    function showUserDetails(userDetails) {
        /** HTML tablosunu temizle */
        $('#custom-table-container').empty();

        /** Kullanıcı detaylarını içeren HTML'i oluşturuyoruz **/
        var detailsHtml = '<div>';
        detailsHtml += '<p>ID: ' + userDetails.id + '</p>';
        detailsHtml += '<p>Name: ' + userDetails.name + '</p>';
        detailsHtml += '<p>Username: ' + userDetails.username + '</p>';
        detailsHtml += '<p>Email: ' + userDetails.email + '</p>';
        detailsHtml += '<p>Phone: ' + userDetails.phone + '</p>';
        detailsHtml += '<p>Website: ' + userDetails.website + '</p>' + '<hr>';
        detailsHtml += '<p>Address: ' + userDetails.address.street + ' ' + userDetails.address.suite + ' - ' + userDetails.address.city + '</p>';
        detailsHtml += '<a id="back-button" href="#">&#8592; Listeye Dön:</a>';
        // İhtiyaç duyduğunuz diğer kullanıcı detaylarını ekleyebilirsiniz
        detailsHtml += '</div>';

        /** Oluşturulan HTML'i sayfaya ekliyoruz*/
        $('#custom-table-container').html(detailsHtml);
        /** Tüm listeyi tekrar görüntülemek için geri butonu**/
        $('#back-button').on('click', function(event) {
            event.preventDefault();
            showAllUsers();
        });
    }


});
