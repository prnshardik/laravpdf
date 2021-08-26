<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LaraPDF</title>
        <link rel="stylesheet" href="{{ asset('render.css') }}">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body class="antialiased">
        <div id="container"></div>
        <button type="button" id="button" class="btn btn-primary" style="display:none">High Light</button>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/1.9.661/pdf.min.js" integrity="sha512-6ZNRCYldwdjIX4OfzSaMIy00a5zmXaVzfkHObLKTfSPoieN2Yx+bJKntu2sL/lRhQMRP7LR/44cgqqjQX1grEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/1.9.661/pdf.worker.min.js" integrity="sha512-0jhDohVLbkC5uuJQikXfClVtfa7VjH1P8dOto39cQY4A7q5Zgix3iqOHq23cZhbYhHm1BTgN37PSV+sEk+2/kg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('render.js') }}"></script>
        
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
        <script> 
            let yourstring = '';
        </script>
        <script>
            
            var url = "{{ asset('titan.pdf') }}";

            PDFJS.getDocument(url)
            .then(function(pdf) {
                // Get div#container and cache it for later use
                var container = document.getElementById("container");

                // Loop from 1 to total_number_of_pages in PDF document
                for (var i = 1; i <= pdf.numPages; i++) {

                    // Get desired page
                    pdf.getPage(i).then(function(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport(scale);
                        var div = document.createElement("div");

                        // Set id attribute with page-#{pdf_page_number} format
                        div.setAttribute("id", "page-" + (page.pageIndex + 1));

                        // This will keep positions of child elements as per our needs
                        div.setAttribute("style", "position: relative");

                        // Append div within div#container
                        container.appendChild(div);

                        // Create a new Canvas element
                        var canvas = document.createElement("canvas");

                        // Append Canvas within div#page-#{pdf_page_number}
                        div.appendChild(canvas);

                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        // Render PDF page
                        page.render(renderContext)
                        .then(function() {
                            // Get text-fragments
                            return page.getTextContent();
                        })
                        .then(function(textContent) {
                            // Create div which will hold text-fragments
                            var textLayerDiv = document.createElement("div");

                            // Set it's class to textLayer which have required CSS styles
                            textLayerDiv.setAttribute("class", "textLayer");

                            // Append newly created div in `div#page-#{pdf_page_number}`
                            div.appendChild(textLayerDiv);

                            // Create new instance of TextLayerBuilder class
                            var textLayer = new TextLayerBuilder({
                                textLayerDiv: textLayerDiv, 
                                pageIndex: page.pageIndex,
                                viewport: viewport
                            });

                            // Set text-fragments
                            textLayer.setTextContent(textContent);

                            // Render text-fragments
                            textLayer.render();
                        });
                    });
                }
            });
        </script>

        <script>
            
            window.addEventListener('mouseup', function mousedown(e) {
                yourstring = window.getSelection().toString();
                let pageX = e.pageX;
                let pageY = e.pageY;
                if(yourstring.length > 0){
                    var button = document.getElementById('button');
                    button.style.display = "block";
                    button.style.position = "absolute";
                    button.style.left = parseInt(pageX) + parseInt(10) +'px';
                    button.style.top = parseInt(pageY) + parseInt(10) +'px';
                } else {
                    var button = document.getElementById('button');
                    button.style.display = "none";
                }
                
            });

            $(document).ready(function() {
                $("#button").click(function(e){
                    e.preventDefault();
                    console.log(yourstring);
                    if(yourstring.length > 0){
                    $.ajax({
                        url : "{{ route('save_data') }}",
                        type : 'POST',
                        data :{ "_token": "{{ csrf_token() }}","id": '1',"text" : yourstring},
                        dataType: 'json',
                        async:false,
                        success : function(json){
                            alert('sucess');
                        },
                        error: function(json){
                            if(json.status === 422) {
                                e.preventDefault();
                                var errors_ = json.responseJSON;
                                $.each(errors_.errors, function (key, value) {
                                    alert(key);
                                });
                            }
                        }
                    });
                    }
                    // here have to load ajax and save data in data base 
                    // based on response show alert and hide button

                    $("#button").hide();
                }); 
            });
        </script>
    </body>
</html>
