$(document).ready(function () {
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        container: 'body',
        onConfirm: function () {
            let button = $(this);
            let refreshPage = $('#refreshPage');
            button.prop("disabled", true);
            refreshPage.prop("disabled", true);
            button.find(".spinner-border").removeClass('d-none');

            fetch(config.routes.episodes.sync)
                .then(response => {
                    return response.json();
                })
                .then(() => {
                    button.find(".spinner-border").hide();
                    button.prop("disabled", false);
                });
        },
    });

    $('#dataTable').DataTable({
        processing: true,
        dom: '<"dt-buttons"Bf><"clear">lirtp',
        ajax: config.routes.episodes.index,
        paging: true,
        autoWidth: true,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'air_date', name: 'air_date'},
            {data: 'episode', name: 'episode'},
            {data: 'url', name: 'url'},
            {data: 'created_at', name: 'created_at'},
        ],
        buttons: [
            'colvis',
            'copyHtml5',
            'csvHtml5',
            'excelHtml5',
            'print'
        ]
    });

    $('#dataTable').on('click', 'tr', function () {
        let id = $('td', this).eq(0).text();
        let name = $('td', this).eq(1).text();
        let url = config.routes.episodes.characters.replace('null', id);

        fetch(url)
            .then(response => {
                return response.json();
            })
            .then(jsonResponse => {
                $('#episodeName').html('[' + name + ']');

                const characterDataContainer = document.getElementById('characterDataContainer');

                jsonResponse.forEach(character => {
                    const characterCard = document.createElement('div');
                    characterCard.classList.add('card', 'text-black', 'character-card');

                    const characterImage = document.createElement('img');
                    characterImage.classList.add('card-img', 'character-image');
                    characterImage.style.maxHeight = '300px';
                    characterImage.style.objectFit = 'cover';
                    characterImage.src = character.image;
                    characterImage.alt = 'Character Image';

                    const cardOverlay = document.createElement('div');
                    cardOverlay.classList.add('card-img-overlay');

                    const characterName = document.createElement('h1');
                    characterName.classList.add('card-title', 'character-name', 'text-center', 'font-weight-bold');
                    characterName.textContent = character.name;

                    cardOverlay.appendChild(characterName);
                    characterCard.appendChild(characterImage);
                    characterCard.appendChild(cardOverlay);
                    characterDataContainer.appendChild(characterCard);
                    characterDataContainer.appendChild(document.createElement('hr'));
                });

                $('#characterModal').modal('show');
            });

    });
});
