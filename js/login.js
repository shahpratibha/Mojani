document.addEventListener('DOMContentLoaded', function() {
    var rememberMeContainer = document.querySelector('.remember-me');
    var checkbox = rememberMeContainer.querySelector('input[type="checkbox"]');
    var checkboxCustom = rememberMeContainer.querySelector('.checkbox-custom');

    rememberMeContainer.addEventListener('click', function(event) {
        if (event.target !== checkbox) {
            checkbox.checked = !checkbox.checked;

            var changeEvent = new Event('change', {
                'bubbles': true,
                'cancelable': true
            });
            checkbox.dispatchEvent(changeEvent);
        }
    });
    checkbox.addEventListener('change', function() {
    });

    var form = document.querySelector('.login-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

    });
});
