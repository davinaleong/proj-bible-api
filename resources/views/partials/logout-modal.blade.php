<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                Select "Logout" below if you are ready to end your current session.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Cancel <i class="fas fa-ban"></i>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a class="btn btn-primary" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                         this.closest('form').submit();">
                        Logout <i class="fas fa-sign-out-alt"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
