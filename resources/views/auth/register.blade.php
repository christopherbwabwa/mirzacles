<x-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>
    
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">

                                <x-form.label name="prefixname" />
                                
                                <div class="col-md-6">

                                    <x-form.radio name="Mr" />

                                    <x-form.radio name="Mrs" />

                                    <x-form.radio name="Ms" />
                                
                                    <x-form.error-radio name="prefixname"/>
                                
                                </div>
                               
                            </div>
        
                            <div class="row mb-3">

                                <x-form.label name="firstname"/>

                                <div class="col-md-6">

                                    <x-form.input name="firstname" required/>
                                    <x-form.error name="firstname"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                
                                <x-form.label name="middlename"/>

                                <div class="col-md-6">

                                    <x-form.input name="middlename"/>
                                    <x-form.error name="middlename"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                
                                <x-form.label name="lastname"/>

                                <div class="col-md-6">
                                    <x-form.input name="lastname" required/>
                                    <x-form.error name="lastname"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                               
                                <x-form.label name="suffixname"/>

                                <div class="col-md-6">

                                    <x-form.input name="suffixname"/>
                                    <x-form.error name="suffixname"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                
                                <x-form.label name="username"/>

                                <div class="col-md-6">
                                    
                                    <x-form.input name="username" required/>
                                    <x-form.error name="username"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                
                                <x-form.label name="email"/>

                                <div class="col-md-6">
                                    
                                    <x-form.input name="email" required/>
                                    <x-form.error name="email"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <x-form.label name="photo"/>

                                <div class="col-md-6">
                                    
                                    <x-form.input name="photo" type="file"/>
                                    <x-form.error name="photo"/>
                                   
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <x-form.label name="type"/>
        
                                <div class="col-md-6">

                                    <x-form.input name="type"/>
                                    <x-form.error name="type"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <x-form.label name="password"/>

                                <div class="col-md-6">

                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password" required
                                        autocomplete="new-password">

                                    <x-form.error name="password"/>

                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <x-form.label name="password-confirm"/>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
        
                            <x-form.button> Register</x-form.button>
        
        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-layout>
