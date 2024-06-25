class FastFilter {
    constructor() {
        this.selectCategory = document.querySelector('select[name="category_id"]');
        this.selectElement = document.getElementById('js-fastfilter-select-feat');
        this.selectValue = document.getElementById('js-fastfilter-select-value');
        this.saveButton = document.getElementById('js-save-button');
        this.initialize();
    }

    initialize() {
        this.selectElement.addEventListener('change', () => this.handleSelectChange());
        this.selectValue.addEventListener('change', () => this.toggleSaveButton());
        this.saveButton.addEventListener('click', (event) => this.handleSubmit(event));
    }

    handleSelectChange() {
        const selectedValue = this.selectElement.value;
        const url = `?plugin=fastfilter&action=getfeatvalues&featid=${encodeURIComponent(selectedValue)}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data.data.data); // Для отладки
                if (Array.isArray(data.data.data)) {
                    this.addedValuesList(data.data.data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    addedValuesList(data) {
        this.selectValue.innerHTML = '';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.text = item.value;
            this.selectValue.appendChild(option);
        });

        // Обновляем состояние кнопки сохранения
        this.toggleSaveButton();
    }

    toggleSaveButton() {
        const selectedValues = Array.from(this.selectValue.selectedOptions).map(option => option.value);
        this.saveButton.disabled = selectedValues.length === 0;
    }

    handleSubmit(event) {
        event.preventDefault();

        const selectedValues = Array.from(this.selectValue.selectedOptions).map(option => option.value);
        const selectedTexts = Array.from(this.selectValue.selectedOptions).map(option => option.text);
        const categoryId = this.selectCategory.value;
        const featureId = this.selectElement.value;
        const featureCode = $(this.selectElement).find('option:selected').data('code');
        const featureName = $(this.selectElement).find('option:selected').text();

        if (selectedValues.length === 0) {
            alert('Пожалуйста, выберите хотя бы одно значение.');
            return;
        }

        const params = new URLSearchParams({
            category_id: categoryId,
            feature_id: featureId,
            feature_code: featureCode,
            feature_name: featureName,
            feature_values: JSON.stringify(selectedValues.map((value, index) => ({
                id: value,
                name: selectedTexts[index]
            })))
        }).toString();

        const url = `?plugin=fastfilter&action=savecatparam&${params}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                // Дополнительная логика обработки ответа сервера
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
}

new FastFilter();

$(document).ready(function() {
    $('.fastfilter-table__button').click(function(event) {
        event.preventDefault();
        var settingId = $(this).attr('href').split('id=')[1];
        if (confirm('Вы уверены, что хотите удалить эту запись?')) {
            $.ajax({
                url: '?plugin=fastfilter&action=delete',
                type: 'POST',
                dataType: 'json',
                data: { id: settingId },
                success: function(response) {
                    if (response.status === 'ok') {
                        location.reload(); // Перезагрузка страницы после успешного удаления
                    } else {
                        alert('Ошибка: ' + response.errors.join(', '));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Ошибка: ' + error);
                }
            });
        }
    });
});
