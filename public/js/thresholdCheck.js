//Threshold
//Calculate threshold correction real
var thresholdField = document.getElementById('threshold');
var thresholdCorrectionField = document.getElementById('thresholdCorrection');
var thresholdCorrectionFormGroup = document.getElementById('thresholdCorrectionFormGroup');
var thresholdReal = document.getElementById('thresholdReal');
var thresholdCorrectionFieldHelpBlock = document.querySelector('#thresholdCorrectionFormGroup .help-block');

function thresholdCheck () {

    if(thresholdField.value || thresholdCorrectionField.value == 0) {

        if(!isNaN(thresholdField.value) && !isNaN(thresholdCorrectionField.value)) {
            thresholdCorrectionFieldHelpBlock.innerHTML = '';
            thresholdCorrectionFormGroup.classList.remove("has-error");
            thresholdReal.value = Math.round((+thresholdField.value + +thresholdCorrectionField.value) * 100) / 100;
        } else {
            thresholdCorrectionFieldHelpBlock.innerHTML = 'Drempelwaarde correctie moet een nummer zijn';
            thresholdCorrectionFormGroup.classList.add("has-error");
        }

    } else {
        thresholdCorrectionFieldHelpBlock.innerHTML = 'Selecteer eerst een categorie met een drempelwaarde voordat je een correctie gaat toepassen. Vul anders het getal 0 in.';
        thresholdCorrectionFormGroup.classList.add("has-error");
    }
}

function thresholdReset() {
    thresholdField.value = '';
    thresholdCorrectionField.value = 0;
    thresholdReal.value = '';
}

thresholdCorrectionField.addEventListener('keyup', function (e) {
    thresholdCheck();
});

//ajax category threshold
var categorySelect = document.getElementById('category');
categorySelect.onchange = function (e) {

    thresholdReset();

    var categoryId = categorySelect.options[categorySelect.selectedIndex].value;

    $.ajax({
        url: "/categories/threshold",
        type: 'get',
        data: {
            categoryId: categoryId,
            _token: $('meta[name="csrf-token"]').attr('content'), //cstf token
        },
        success: function (result) {

            if (result.threshold == null) {
                document.getElementsByName('threshold')[0].value = '';
                document.getElementsByName('threshold')[0].placeholder = 'Geen';
            } else {
                document.getElementsByName('threshold')[0].value = result.threshold;
            }

        },
        error: function (e) {
            console.log(e);
        }
    });

    thresholdCheck();
};