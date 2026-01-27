<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        input{
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <div id="searchResult"></div>

    <!-- 
       1. Create a script to watch if user type keys in the input
       2. Fetch data from database
       3. Display results
     -->

<script src="js/jquery/jquery-3.7.1.min.js"></script>
<script>
    var typingTimer;
    var doneTypingInterval = 500;

    document.addEventListener('keyup', function(ev){
        let el = ev.target;

        if(el.id === 'searchInput'){
            let searchTerm = el.value;
            clearTimeout(typingTimer);

            typingTimer = setTimeout(function(){
                searchDb(searchTerm);
            }, doneTypingInterval);
        }
    });

    function searchDb(searchTerm){
        let searchResult = document.getElementById('searchResult');

        if(searchTerm.length){
            searchResult.style.display = 'block';

            $.ajax({
                type: 'GET',
                data: {search_term: searchTerm},
                url: 'database/live-search.php',
                dataType: 'json',
                success: function(response){
                    if(response.total === 0){
                        searchResult.innerHTML = 'no data found';
                    } else {
                        let html = '';

                        for (const [tbl, tblRows] of Object.entries(response.data)) {
                            tblRows.forEach((row) => {
                                let text = '';
                                let url = '';

                                if (tbl === 'users') {
                                    text = row.first_name + ' ' + row.last_name;
                                    url = 'users-view.php';
                                }

                                if (tbl === 'suppliers') {
                                    text = row.supplier_name;
                                    url = 'supplier-view.php';
                                }

                                if (tbl === 'products') {
                                    text = row.product_name;
                                    url = 'product-view.php';
                                }

                                html += '<a href="' + url + '">' + text + '</a><br>';
                            });
                        }

                        searchResult.innerHTML = html;
                    }
                }
            });

        } else {
            searchResult.style.display = 'none';
        }
    }
</script>

    
</body>
</html>