window.addEventListener('load', ajaxCall);

function ajaxCall() {
    $.getJSON("api/news/")
        .done(loadNewsChart)
        .fail(function () {
            console.log("fail")
        });

    $.getJSON("categories/json")
        .done(loadCategoriesChart)
        .fail(function () {
            console.log("fail")
        });
}

function loadNewsChart(data) {
    var ctx = document.getElementById("newsChart");

    var days = [];
    for(var i = 6; i >= 0; --i) {
        var day = moment().subtract(i, 'days').format('MMMM Do YYYY');
        days.push(day);
    }
    var averageThisWeek = calculateAverage(data);
    var messageCounts = countMessages(data, days.length);

    var newsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                data: messageCounts,
                label: 'Vandaag',
                borderColor: "#3e95cd",
                lineTension: 0,
                fill: false
            }, {
                data: [averageThisWeek, averageThisWeek, averageThisWeek, averageThisWeek, averageThisWeek, averageThisWeek, averageThisWeek],
                label: 'Gemiddeld',
                borderColor: "#ff0000",
                lineTension: 0,
                fill: false,
                borderDash: [10, 4]
            }]
        }
    });

    function countMessages(data, days) {
        var messageCounts = [];
        for(var i = days - 1; i >= 0; --i) {
            var count = 0;
            var today = moment().subtract(i, 'days').format('MMMM Do YYYY');

            for(var j in data) {
                if(moment(data[j].created_at.date).format('MMMM Do YYYY') === today) {
                    ++count;
                }
            }
            messageCounts.push(count);
        }
        return messageCounts;
    }

    function calculateAverage(data) {
        var count = 0;
        var today = moment().subtract(6, 'days');
        for(var i in data) {
            if(moment(data[i].created_at.date).diff(today, 'days') >= 0) {
                ++count;
            }
        }
        return count / 7;
    }
}

function loadCategoriesChart(data) {
    var ctx = document.getElementById("assetChart");

    var categoryCount = [];
    var categoryName = [];
    for(var i in data) {
        categoryCount.push(data[i].assets_count);
        categoryName.push(data[i].name);
    }
    var categoryLegend = generateColors(categoryCount.length);

    var categoriesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryName,
            datasets: [{
                data: categoryCount,
                backgroundColor: categoryLegend
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });

    function generateColors(count) {
        var letters = '0123456789ABCDEF';
        var colorArray = [];

        for(var i = 0; i < count; ++i) {
            var color = '#';
            for(var j = 0; j < 6; ++j) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            colorArray.push(color);
        }
        return colorArray;
    }
}