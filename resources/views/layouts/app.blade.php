<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smart Education</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ url('css/images/thumbnail.png') }}">
    <style>
        main{
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: url("/css/images/background2.jpg") center bottom;
            background-size: cover;
            z-index: 10;
            position: absolute;
            margin-top: 50%;
        }
        main>section{
            width: 28.5%;
            max-height: 25em;
            margin: 0 1.5% 0 1.5%;
            font-size: 1.1em;
            padding: 2%;
            transition: all .4s;
            color: #282828;
            box-shadow: 0 0 .15em #888;
            border-radius: 3px;
        }
        main>section:hover{
            box-shadow: -0.15em -0.15em 0.8em -0.1em #555,
                        0.15em -0.15em 0.8em -0.1em #555;
        }
        main>section>h4{
            font-size: 2.2em;
            font-family: "Calibri Light";
            color: #050505;
            text-shadow: .005em .005em .01em rgb(132, 206, 235);
            border-bottom: 1px solid transparent;
            transition: all .5s;
        }

        @media only screen and (max-width: 800px) {
            main{
                height: auto;
                display: inline-block;
                margin-top: 150%;
            }
            main>section{
                width: auto;
                font-size: 1em;
                padding: 1%;
            }
            main>section>h4{
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav id="mainNav" class="navbar navbar-default navbar-static-top">

                <div class="navbar-header">

                    <a id="logo"  href=" @if(Auth::guest()) {{  url('/') }} @else {{ url('/home') }} @endif "><img src="{{url('css/images/logo-final.png')}}"></a>

                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @guest
                            <li id="logButton"><a href="{{ route('login') }}">Влез</a></li>
                            <li id="regButton"><a href="{{ route('register') }}">Регистрация</a></li>
                        @else

                            <li class="dropdown" onmouseenter="$(this).siblings().addClass('whitened');" onmouseleave="$(this).siblings().removeClass('whitened');">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Тестове <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">

                                    <li><a href="{{ url('/test/create') }}">Създай тест</a></li>
                                    <li><a href="{{ url('/test/mine') }}">Моите тестове</a></li>
                                    <li><a href="{{ url('/test/verify') }}">Непроверени тестове</a></li>
                                    <li><a href="{{ url('/test/verified') }}">Проверени тестове</a></li>

                                </ul>
                            </li>

                            <li class="dropdown" onmouseenter="$(this).siblings().addClass('whitened');" onmouseleave="$(this).siblings().removeClass('whitened');">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Анкети <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">

                                    <li><a href="{{ url('/poll/create') }}">Създай анкета</a></li>
                                    <li><a href="{{ url('/poll/mine') }}">Моите анкети</a></li>

                                </ul>
                            </li>

                            <li class="dropdown" onmouseenter="$(this).siblings().addClass('whitened');" onmouseleave="$(this).siblings().removeClass('whitened');">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Излез
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
        </nav>

        @if(Auth::guest() && $_SERVER['REQUEST_URI'] == '/')
            <div id="welcome">
                <div>
                    @yield('content')
                </div>
            </div>

            <main>
                <section>
                    <h4>Кратко описание:</h4>
                    <br>
                    Проектът представлява интернет приложение и е предназначен за всички учители, които искат лесно и удобно да създават тестове, да ги организират в папки и да ги споделят за онлайн решаване от учениците или искат да разберат мнението им по даден въпрос, чрез създаването на анкета.</section>
                <section>
                    <h4>Предимства:</h4>
                    <br>
                    Smart Education има предимство пред другите подобни приложения със своя опростен дизайн, удобен за изполване интерфейс и съчетаването на много различни функционалности на едно място.
                    Едно от най-големите преимущества е, че учениците могат да решават тестовете без да се регистрират, което създава по-голямо удобство за тях.
                </section>
                <section>
                    <h4>Контакти:</h4>
                    <br>
                    <section>
                        <p>
                            Симеон Стойнев
                        </p>
                        <p>
                            <span class="glyphicon glyphicon-envelope"></span>
                            l70etc@abv.bg
                        </p>
                    </section>
                    <section>
                        <p>
                            Денислав Колев
                        </p>
                        <p>
                            <span class="glyphicon glyphicon-envelope"></span>
                            denislav_kolev00@abv.bg
                        </p>
                    </section>
                    <p>II СУ „Проф. Никола Маринов“</p>
                    <p>гр. Търговище</p>
                </section>
            </main>
        @else
            @yield('content')
        @endif
    </div>

    <!-- Scripts -->

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="{{ url('js/jquery-sortable.js') }}"></script>
    <script src="{{ url('js/clipboard.min.js') }}"></script>
    <script>
        var $ =  jQuery.noConflict();

        checkAnswers();
        $('.glyphicon-plus').on('click', function () {
            if($('.keeper').size() < 8){
                $('.keeper').last().after(
                    '<li class="col-md-12 keeper sortable"><label class="col-md-11 create_answers"><input class="col-md-12" type="text" name="answers[]"/>'
                    +
                    '</label><button class="delPoll" type="button">X</button></li>');
                $('.create_answers input').each(function(index){
                    index++;
                    $(this).attr("placeholder", "Отговор " + index);
                });
                checkAnswers();
                deleteItem();
            }
            else{
                alert('Анкетата не може да съдържа повече от 8 отговора.')
            }
        });
        function checkAnswers() {
            var items = $('.keeper');
            if (items.size() <= 2) {
                $('.keeper').removeClass('showMinus');
            }
            else {
                $('.keeper').addClass('showMinus');
            }
        }
        checkAnswers();
        function deleteItem() {
            $('.delPoll').on('click', function () {
                if($('.keeper').size() > 2){
                    $(this).parent('.keeper').remove();
                    $('.create_answers input').each(function( index ) {
                        index++;
                        $(this).attr("placeholder", "Отговор " + index);
                    });
                    checkAnswers();
                }
                else{
                    $('.keeper').removeClass('showMinus');
                }
            })
        }

        function deleteAnswer(el) {
            $(el).parent('.keeper').remove();
            $('.create_answers input').each(function( index ) {
                index++;
                $(this).attr("placeholder", "Отговор " + index);
            });
            checkAnswers();
        }

        $('.input-group-addon').on('click', function () {
            $(this).parent('.front').find('.selectedAnswer').removeClass('selectedAnswer');
            $(this).addClass('selectedAnswer');
        });

        var M = $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            gutter:'.gutter-sizer',
            fitWidth: true,
            percentPosition: true
        });

        function removeError(){
            $('.pollError button' ).on('click', function(){
                $(this).parent().fadeOut(600, function(){ $(this).remove(); });
                M.masonry();
            });
        }

        function setResults(results , el){
            $('label .gradient > div', el ).each( function(i){
                var p = Math.ceil( ( results['a'+i] / results.allItems  ) * 100  ) + '%';
                if( p === "NaN%" ){
                    $('div', this).html('0%');
                }
                else{
                    $(this).css('left' , p);
                    $('div', this).html(p);
                }
            });
        }

        function submit_vote(el, id){
            if ($('input:checked', el).size() > 0) {
                $.ajax({
                    type: "POST",
                    url: el.attr('action'),
                    data: {
                        id: id, answer: $('input:checked', el).val(), _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('.pollError', el).remove();
                        $('.front h2',el).after( data.error );

                        if(typeof data.error !== "undefined"){
                            removeError();
                        }
                        if(typeof data.results !== "undefined"){
                            setResults(data.results, el);
                        }
                    }
                });
            }
            else {
                $('.pollError',el).remove();
                $('.front h2',el).after( ' <p class="pollError">Няма избран отговор!<button class="delPoll" type="button">X</button></p>' );
                removeError();
                M.masonry();
            }
        }

        function get_results(el, id){
            $.ajax({
                url: "/results/"+id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {

                    $('.front h2',el).after( data.error );

                    if( typeof data.error !== "undefined" ){
                        removeError();
                    }
                    if( typeof data.results !== "undefined" ){
                        setResults(data.results , el );
                    }
                    M.masonry();
                }
            });
        }

        function del_poll(id){
            $.ajax({
                url: "/poll/delete/"+id,
                type: "POST",
                data:{
                    id: id, _token: "{{ csrf_token() }}"
                },
                success: function(){
                    location.reload();
                }
            });
        }

        function create_folder(el){
            $(el).siblings(".currentUrl").val(window.location.pathname);
            $(el).next('.showCreateFold').fadeIn(500);
            $('.showCreateFold input').removeAttr('disabled');
        }

        function delete_folder(id){
            $.ajax({
                url: "/delete/folder/"+id,
                type: "POST",
                data:{
                    id: id, _token: "{{ csrf_token() }}"
                },
                success: function(){
                    location.reload();
                }
            });
        }

        function add_closedTask(parentEl) {
            var closedTask = $(".closedTask").first().html();
            $(".task:last").after("<li class='col-md-12 task'>" + closedTask + "</li>");
            $(".task:last").find("input[disabled]").removeAttr('disabled');
            $(".task:last").find("select[disabled]").removeAttr('disabled');
            $(".task:last").find("option[disabled]").removeAttr('disabled');
            checkElements();
            $(parentEl).fadeOut(400);
        }

        function add_openTask(parentEl) {
            var openTask = $(".openTask").first().html();
            $(".task:last").after("<li class='col-md-12 task'>" + openTask + "</li>");
            $(".task:last").find("input[disabled]").removeAttr('disabled');
            checkElements();
            $(parentEl).fadeOut(400);
        }

        function sortableTasks(){
            $("#createTest_form > #task_keeper").sortable(
                {
                    handle: '.handle',
                    axis: "y"
                }
            );
        }
        sortableTasks();

        function delete_task(el) {
            $(el).parent("li.task").remove();
            checkElements();
        }

        function checkElements() {
            var ElementsNum = $("li.task").size();
            if(ElementsNum <= 3){
                $("li.task").find("input.removeDisabled[disabled]").removeAttr('disabled');
            }
            else{
                $("li.task").find("input.removeDisabled").prop('disabled', true);
            }
        }

        function checkEvalGrid(currentValue) {
            return $(currentValue).val() !== '';
        }
        
        function activateTest(id) {
            var evalGrid = [
                $('.evalGrid')[0], $('.evalGrid')[1], $('.evalGrid')[2], $('.evalGrid')[3], $('.evalGrid')[4], $('.evalGrid')[5], $('.evalGrid')[6], $('.evalGrid')[7], $('.evalGrid')[8], $('.evalGrid')[9]
            ];

            if($('#students_number').val() == ''){
                alert("Трябва да попълните поле Брой ученици, за да активирате тест.");
            }
            else if($('#grade').val() == ''){
                alert("Трябва да попълните поле Клас, за да активирате тест.");
            }
            else if($('#sub_class').val() == ''){
                alert("Трябва да попълните поле Паралелка, за да активирате тест.");
            }
            else if(evalGrid.every(checkEvalGrid) != true){
                alert("Трябва да попълните скалата за оценяване, за да активирате тест.");
            }
            else{
                $.ajax({
                    url: "/activate/test/"+id,
                    type: "POST",
                    data:{
                        id: id,
                        students:  $('#students_number').val(),
                        grade: $('#grade').val(),
                        sub_class: $('#sub_class').val(),
                        evalGrid0: $(evalGrid[0]).val(),
                        evalGrid1: $(evalGrid[1]).val(),
                        evalGrid2: $(evalGrid[2]).val(),
                        evalGrid3: $(evalGrid[3]).val(),
                        evalGrid4: $(evalGrid[4]).val(),
                        evalGrid5: $(evalGrid[5]).val(),
                        evalGrid6: $(evalGrid[6]).val(),
                        evalGrid7: $(evalGrid[7]).val(),
                        evalGrid8: $(evalGrid[8]).val(),
                        evalGrid9: $(evalGrid[9]).val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(){
                        location.reload();
                    }
                });
            }
        }

        function closeTest(id) {
            $.ajax({
                url: "/close/test/"+id,
                type: "POST",
                data:{
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(){
                    location.reload();
                }
            })
        }

        function selectAnswer(el) {
            $(el).parent().siblings('.shuffleAns').children('input:checked').prop('checked', false);
            $(el).prop('checked', true);
            $(el).parent().parent().next().removeAttr('name').removeAttr('val');
        }

        function getImageName(el) {
            var imageName = el[0].files[0].name;
            $(el).next().html(imageName);
            $(el).next().next().val(1);
        }

        function renameFolder(el){
            $(el).parent().parent().siblings(".currentUrl").val(window.location.pathname);
            $(el).prev().fadeOut(0);
            $(el).fadeOut(0);
            $(el).siblings(".renameFolder").find("input").removeAttr('disabled');
            $(el).siblings(".renameFolder").fadeIn(250);
        }

        function closeFolderRename(el) {
            $(el).siblings("label").children().attr('disabled', true).parent().parent().fadeOut(0);
            $(el).parent().next().fadeIn(250);
            $(el).parent().next().next().fadeIn(250);
        }

        function closeCreateFolder(el) {
            $(el).siblings("label").children().attr('disabled', true).parent().parent().fadeOut(0);
        }

        new ClipboardJS('.copyPollLink', {
            text: function(trigger) {
                console.log(trigger.previousSibling.previousSibling.innerHTML);
                return trigger.previousSibling.previousSibling.getAttribute('href');
            }
        });

        function searchTest(input) {
            var searchedData = input.val();
            var foundElement = $(input).parent().siblings('.listing').find('li:contains('+searchedData+')');
            $(input).parent().siblings('.listing').children('li:first-of-type').before(foundElement);
        }

        function viewImage(el) {
            var imageSrc = $(el).attr('src');
            var imageWidth = $(el).width();
            $('#whitebox').css('visibility', 'visible').children('img').attr('src', imageSrc).css('margin-left','50%-',imageWidth,'');
        }

        if($('#randomizeTasks').length){
            var allTasks = $(".task").length;
            for(var i = 0; i<allTasks; i++){
                var randNumber = 1 + Math.floor(Math.random() * allTasks);
                var currentTask = $(".task")[randNumber];
                for(var c = 0; c<4; c++){
                    var randAnswerNumber = 1 + Math.floor(Math.random() * 3);
                    $(currentTask).find(".shuffleAns:first-of-type").before($(currentTask).find(".shuffleAns")[randAnswerNumber]);
                }
                $(".task:first-of-type").before($(currentTask));

                var currentTaskAnswer = $(currentTask).find(".taskAns");
                for(var currentNumAnswer = 0; currentNumAnswer<4; currentNumAnswer++){
                    var elToBeSet = $(currentTaskAnswer)[currentNumAnswer];
                    if(currentNumAnswer == 0){
                        $(elToBeSet).html("A) ");
                    }
                    else if(currentNumAnswer == 1){
                        $(elToBeSet).html("Б) ");
                    }
                    else if(currentNumAnswer == 2){
                        $(elToBeSet).html("В) ");
                    }
                    else{
                        $(elToBeSet).html("Г) ");
                    }
                }
            }
        }

        function invalidInput(el) {
            var textfield = $(el).get(0);

            textfield.setCustomValidity('');

            if (!textfield.validity.valid) {
                textfield.setCustomValidity('Моля попълнете това поле.');
            }
        }

        function changeInputText(el) {
            var elementValue = $(el).val();
            var options = [
                $(el).siblings(".select_ra").find('option')[0], $(el).siblings(".select_ra").find('option')[1], $(el).siblings(".select_ra").find('option')[2], $(el).siblings(".select_ra").find('option')[3]
            ];

            if($(el)[0] == $(el).parent().find('input:text')[0]){
                $(options[0]).val(elementValue);
            }
            else if($(el)[0] == $(el).parent().find('input:text')[1]){
                $(options[1]).val(elementValue);
            }
            else if($(el)[0] == $(el).parent().find('input:text')[2]){
                $(options[2]).val(elementValue);
            }
            else{
                $(options[3]).val(elementValue);
            }
        }

        function changeMaxScorePerTask(){
            var newMaxPoints = 0;
            if($('#evaluationGrid').length){
                $('#task_keeper').find('input[type=number]:enabled').each(function () {
                    newMaxPoints += parseFloat($(this).val());
                });
                $('#evaluationGrid .evalGrid:last-of-type').attr('max', newMaxPoints);
            }
        }
    </script>
</body>
</html>
