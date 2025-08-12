<div class="mb-3">
    <label class="form-label">{{ trans('plugins/hall-of-fame::settings.pgp.public_key') }}</label>
    <input type="file" class="form-control" accept=".asc,.pgp,.gpg,text/plain"
        onchange="(function(el){
        const reader = new FileReader();
        reader.onload = function(){
            const ta = document.querySelector('textarea[name=hall_of_fame_pgp_public_key]');
            if (ta) ta.value = reader.result;
        };
        if (el.files && el.files[0]) reader.readAsText(el.files[0]);
    })(this)">
</div>
<div class="mb-3">
    <label class="form-label">{{ trans('plugins/hall-of-fame::settings.pgp.private_key') }}</label>
    <input type="file" class="form-control" accept=".asc,.pgp,.gpg,text/plain"
        onchange="(function(el){
        const reader = new FileReader();
        reader.onload = function(){
            const ta = document.querySelector('textarea[name=hall_of_fame_pgp_private_key]');
            if (ta) ta.value = reader.result;
        };
        if (el.files && el.files[0]) reader.readAsText(el.files[0]);
    })(this)">
</div>
