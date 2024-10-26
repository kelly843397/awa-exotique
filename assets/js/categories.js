// Fonction pour récupérer les catégories via fetch et les afficher
function fetchCategories() {
    fetch('/awa-exotique/categories') // URL de ta méthode index pour récupérer les catégories
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            const categoriesList = document.getElementById('categories-list');
            categoriesList.innerHTML = ''; // Vider la liste actuelle

            if (data.length > 0) {
                data.forEach(category => {
                    let listItem = `<li>${category.name}`;

                    // Si l'utilisateur est un administrateur, afficher les liens Modifier et Supprimer
                    if (isAdmin) {
                        listItem += `
                            <a href="/awa-exotique/edit?id=${category.id}">Modifier</a> |
                            <a href="/awa-exotique/delete?id=${category.id}">Supprimer</a>`;
                    }

                    listItem += `</li>`;
                    categoriesList.innerHTML += listItem;
                });
            } else {
                categoriesList.innerHTML = '<li>Aucune catégorie trouvée.</li>';
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des catégories:', error));
}

// Charger les catégories au chargement de la page
document.addEventListener('DOMContentLoaded', fetchCategories);
