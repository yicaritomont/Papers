<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" aria-expanded="{{ $closed or 'true' }}" aria-controls="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
                {{ $title or 'Override Permissions' }} {!! isset($user) ? '<span class="text-danger">(' . $user->getDirectPermissions()->count() . ')</span>' : '' !!}
            </a>
        </h4>
    </div>
    <div id="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}" class="panel-collapse collapse {{ $closed or 'in' }}" role="tabpanel" aria-labelledby="dd-{{ isset($title) ? str_slug($title) :  'permissionHeading' }}">
        <div class="panel-body">
            <div class="row">   
                {{$antes_role = ""}}
                <?php $i = 0;?>
                @foreach($permissions as $perm)
                    
                    <?php
                        $abilities = ['view_','add_','edit_','delete_'];
                        $permission = str_replace($abilities,'',$perm->name);
                        
                        $per_found = null;

                        if( isset($role) ) {
                            $per_found = $role->hasPermissionTo($perm->name);
                        }

                        if( isset($user)) {
                            $per_found = $user->hasDirectPermission($perm->name);
                        }

                        if($permission != $antes_role)
                        {
                            ?>
                            <div class="col-sm-12 col-md-12 panel-header-form" >
                                {{ strtoupper($permission)}}
                            </div>
                            
                            <?php                            
                        }
                        $antes_role = $permission;
                       
                    ?>
                       
                    <!--<div class="collapse" id="collapseExample{{$i}}">
                        <div class="card card-body">
                            <div class="col-md-3">
                                <div class="checkbox">
                                    <label class="{{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">
                                        {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ $perm->name }}
                                    </label>
                                </div>
                            </div> 
                        </div>
                    </div>-->
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label class="{{ str_contains($perm->name, 'delete') ? 'text-danger' : '' }}">
                                {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ $perm->name }}
                            </label>
                        </div>
                    </div>
                    <?php  $i++;?>
                @endforeach
            </div>
        </div>
    </div>
</div>