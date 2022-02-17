
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Классификатор</title>

    {# Load the tag library #}
    {% load bootstrap4 %}

    {# Load CSS and JavaScript #}
    {% bootstrap_css %}
    {% bootstrap_javascript jquery='full' %}

    {# Display django.contrib.messages as Bootstrap alerts #}
    {% bootstrap_messages %}

    <script type="text/javascript">
            function make_prediction() {
                url = 'http://127.0.0.1:8000';
                url = url + '/make_prediction';
                $('#error').addClass('d-none');
                $('#warning').removeClass('d-none');
                $('body').css('cursor', 'progress');
                $.post(url, {'csrfmiddlewaretoken': '{{ csrf_token }}'})
                    .done (function(data) {
                        $('body').css('cursor', 'default');
                        $('#warning').addClass('d-none');
                        if (!$('#d_table').length) {
                            $('#u_table').append($('<div class="table-responsive" id="d_table"></div>'));
                            $('#d_table').append(data.csv_table);
                            $('thead').addClass('thead-light');

                            $('#u_statistics').removeClass('d-none');
                            $('#u_statistics').append($('<div class="table-responsive" id="d_statistics"></div>'));
                            $('#d_statistics').append(data.statistics);
                            $('#_all').append(data.cnt_all + ' сообщений');
                        }
                    })
                    .fail(function() {
                            $('body').css('cursor', 'default');
                            $('#warning').addClass('d-none');
                            $('#error').removeClass('d-none');
                            console.log( "Ошибка с обработкой файла (см. Терминал)" );
                          });
            }
            function download_csv() {
                url = 'http://127.0.0.1:8000';
                url = url + '/download';

                window.open(url);
            }
    </script>

</head>
<body>


<div class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Система анализа данных</h1>
        <div class="input-group">
            <form method="POST" enctype="multipart/form-data" class="form">
                {% csrf_token %}
                {% bootstrap_form form %}
                {% buttons %}
                <button type="submit" class="btn btn-primary">Загрузить данные CSV</button>
                {% endbuttons %}
            </form>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-outline-secondary" onclick="make_prediction()">Сделать прогноз
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="download_csv()">Скачать новые данные
                    CSV
                </button>
            </div>
        </div>
    </div>
    <div class="alert alert-info alert-dismissible fade show d-none" role="alert" id="info">
        Файл {{ file_result }} успешно загружен!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="error">
        Ошибка! Проверьте, что Вы загрузили файл в систему.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="text-center d-none" id="warning">
        <img src='https://www.mrplan.es/experiencias/images/loading_23.gif' alt='loading' style='width: 250px'/>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4 d-none" id="u_statistics">
            <h4 class="text-muted">ОБЩАЯ СТАТИСТИКА</h4>
            <span class="text-muted font-weight-bold" id="_all"></span>
        </div>
        <div class="col-md-8 order-md-1" id="u_table"></div>
    </div>
</div>

</body>
</html>
