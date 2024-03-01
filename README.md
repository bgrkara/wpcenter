# WP Custom Endpoint Plugin

Bu eklenti, özel bir REST API uç noktası ekleyerek kullanıcı detaylarını almayı sağlar.

## Kurulum

1. Eklentiyi WordPress'in `plugins` dizinine yükleyin.
2. WordPress yönetici panelinde Eklentiler bölümünden eklentiyi etkinleştirin.
3. Yeni bir sayfa oluşturarak `<div id="custom-table-container"></div>` belirtilen tag'i HTML olarak sayfa içeriğine ekleyiniz.
4. Oluşturduğunuz sayfa içeriğinden user listesini kontrol edebilirsiniz. 

## Kullanım

Eklenti, aşağıdaki özel REST API uç noktalarını ekler:

- `/custom/v1/endpoint/`: Tüm kullanıcıları getiren basit bir uç nokta.
- `/custom/v1/user-details/{id}`: Belirli bir kullanıcının detaylarını getiren uç nokta.

Bu uç noktalara HTTP GET isteği yaparak veri alabilirsiniz.

Örnek kullanım:

- Tüm kullanıcıları getir: `http://your-site.com/wp-json/custom/v1/endpoint/`
- Kullanıcı detaylarını getir: `http://your-site.com/wp-json/custom/v1/user-details/1`

## Notlar

- Bu eklenti, WordPress REST API'yi kullanarak uzak bir veri kaynağından veri çeker.
- Özel JavaScript dosyası, AJAX kullanarak kullanıcı detaylarını sayfada gösterir.
- Önbellek kullanarak sayfa yüklemelerini optimize eder.

## Geliştirme

Eğer eklentiyi geliştirmek istiyorsanız, kodları `wpcenter-custom-endpoint.php` dosyasında bulabilirsiniz.
Postman Collection Eklenti dosyasının içerisinde mevcut `WPCenter.postman_collection.json` dosyasını postman/insomnia üzerinde import ederek inceleyebilirsiniz 
