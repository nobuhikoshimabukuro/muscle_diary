{{-- 再ログインモーダル--}}
<div class="modal fade" id="login_again-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="login_again-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id=""></h5>               
            </div>

            <div class="modal-body">
                一定の時間が経過した為、ログイン者の情報が取得できません。
                <br>
                再ログインをお願い致します。               
            </div>

            <div class="modal-footer">
                <button type='button' class='btn btn-success page-transition-button' data-url='{{ route('management.login') }}'>ログイン画面</button>
            </div>

        </div>
    </div>
</div>