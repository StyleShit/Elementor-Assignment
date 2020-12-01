const onlineUsers   = _( '.online-users tbody' );
const updatedAt     = _( '.last-updated span' );
const userName      = _( '.user-name' );

window.addEventListener( 'load', ( e ) => {

    const currentUser = _apiGetCurrentUser();

    // add welcome message
    userName.innerText = currentUser.email;

    // set user as online
    _apiGoOnline();

    // fetch online users every 3 seconds
    fetchOnlineUsers();

    setInterval( () => {

        fetchOnlineUsers();

    }, 3000 );

});


window.addEventListener( 'beforeunload', async ( e ) => {

    e.preventDefault();

    // set user as offline
    await _apiGoOffline();

});


// fetch online users into the table
const fetchOnlineUsers = () => {

    _apiGetOnlineUsers()
        .then( res => {

            const users = res.data;
            let output = '';

            // build the table structure
            users.forEach( ( user ) => {

                const loggedAt = new Date( user.loggedAt * 1000 ).toLocaleString();

                const row = `
                    <tr data-user-id="${ user.id }">
                        <td>${ user.email }</td>
                        <td>${ loggedAt  }</td>
                        <td>${ user.ip }</td>
                    </tr>
                `;

                output += row;

            });


            // set the data into the table
            onlineUsers.innerHTML = output;
            updatedAt.innerText = new Date().toLocaleTimeString();

            bindUserClick();

        });

};


// add click event to users rows
const bindUserClick = () => {

    const rows   = __( '.online-users tbody tr' );

    rows.forEach( ( row ) => {

        row.addEventListener( 'click', () => {

            const userId = row.dataset.userId;
            showUserModal( userId );

        });

    });

}


// show user data in a modal by id
const showUserModal = ( id ) => {

    _apiGetUserData({ id })
        .then( res => {

            if( !res.error )
            {
                console.log( res.data[0] );
            }

        });

    // TODO: implement

}