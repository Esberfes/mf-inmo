@extends('admin.admin-layout')

@section('content')

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session()->has('success'))
<div class="alert alert-success mt-3">
    {{ session()->get('success') }}
</div>
@endif


<section class="admin-table-section">
    <div class="card mb-3 admin-table-wrapper">
        <div class="admin-table-wrapper-header">
            <div class="admin-table-wrapper-header-title">
                Usuarios WS
            </div>
            <div class="admin-table-wrapper-header-info">
                <i id="refresh" class="fas fa-sync-alt"></i>
            </div>
        </div>
        <div class="admin-table-wrapper-body">

            <table class="admin-table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ip</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">URL</th>
                        <th scope="col">Ultima actualización</th>
                    </tr>
                </thead>
                <tbody id="tableDataBody">

                </tbody>
            </table>
        </div>
        <div class="admin-table-wrapper-footer d-flex justify-content-center">
            <small class="ml-5">Estado del servidor: <span><b id="serverStatus">comprobando...</b></span> </small>
        </div>
    </div>
</section>
<style>
    .admin-table-wrapper .admin-table td {
        width: 20% !important;
        overflow: hidden !important;
        display: inline-block !important;
        height: 2.2rem;
    }

    .admin-table-wrapper .admin-table th {
        width: 20% !important;
        overflow: hidden !important;
        display: inline-block !important;
    }
</style>
@endsection

@section('scripts')
<script>
    class ComponentValue {
        constructor(element, value, parent) {
            this.element = element;
            this.value = value;
            this.parent = parent;
            this.parent.append(this.element);
            this.changeValue(this.value);
        }

        changeValue(value) {
            this.value = value;
            this.updateValue();
        }

        updateValue() {
            setTimeout(() => {
                this.element.text(this.value);
                this.element.attr('title', this.value);
            }, 100);

        }
    }

    $(document).ready(function() {
        var tBody = $("#tableDataBody");
        var serverStatus = $("#serverStatus");

        setInterval(function() {
            axios.get('/push/ping_server')
                .then(response => {
                    serverStatus.text("OK");
                })
                .catch(e => {
                    serverStatus.text("Sin conexión");
                    console.log(e);
                });
        }, 5000);

        var users = [];
        var conn = window.Echo.channel('activities-admins').listen('ActivityEventAdmin', (data) => {
            if (data && data.message && data.message.discover_user) {
                var user = data.message.discover_user;

                if (users.filter(u => u.id.value == user.id).length > 0) {
                    var oldUser = users.filter(u => u.id.value == user.id)[0];
                    oldUser.ip.changeValue(user.ip);
                    oldUser.date.changeValue(user.date);
                    oldUser.url.changeValue(user.url);
                    oldUser.lastUpdate.changeValue(new Date().toLocaleTimeString());
                } else {
                    var row = $(`<tr></tr>`);
                    var userId = new ComponentValue($(`<td></td>`), user.id, row);
                    var userIp = new ComponentValue($(`<td></td>`), user.ip, row);
                    var userDate = new ComponentValue($(`<td></td>`), user.date, row);
                    var userUrl = new ComponentValue($(`<td></td>`), user.url, row);
                    var lastUpdate = new ComponentValue($(`<td></td>`), new Date().toLocaleTimeString(), row);

                    var userData = {
                        id: userId,
                        ip: userIp,
                        date: userDate,
                        url: userUrl,
                        lastUpdate: lastUpdate
                    };

                    tBody.append(row);

                    users.push(userData);
                }
            }
        });
        console.log(conn);

        window.Echo.connector.pusher.connection.bind('connected', () => {
            refreshData();
            setInterval(() => {
                refreshData();
            }, 5000);
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('disconnected');
        });

        function refreshData() {
            axios.get('/push/ping_on_activity_channel')
                .then(response => {
                    console.log("ping_on_activity_channel")
                })
                .catch(e => {
                    console.log(e);
                });
        }

        $("#refresh").click(function() {
            users = [];
            tBody.empty();
            refreshData();
        });
    });
</script>
@endsection
