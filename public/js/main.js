const film = document.getElementById('film');

if(film)
{
    film.addEventListener('click', e =>
    {
      if(e.target.className === 'btn btn-danger delete-film')
      {
        if(confirm('Êtes-vous sûr de vouloir supprimer ce film ?'))
        {
          const id = e.target.getAttribute('data-id');

          fetch(`/admin/film/delete/${id}`,
            {
            method: 'DELETE'
          }).then(res => window.location.reload());
        }
      }
    });
}

const user = document.getElementById('user');

if(user)
{
    film.addEventListener('click', e =>
    {
      if(e.target.className === 'btn btn-danger delete-user')
      {
        if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?'))
        {
          const id = e.target.getAttribute('data-id');

          fetch(`/admin/user/delete/${id}`,
            {
            method: 'DELETE'
          }).then(res => window.location.reload());
        }
      }
    });
}
