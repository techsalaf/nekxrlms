@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('FTP Hosting root path')
                                        <i class="fas fa-info-circle" title="The URL of your website. This is used to access uploaded files. Include the protocol (http:// or https://)" style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[domain]" value="{{@gs()->ftp->domain}}" placeholder="https://yourdomain.com" required>
                                    <small class="text-muted">@lang('Example: https://yourdomain.com')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('Host')
                                        <i class="fas fa-info-circle" title="Your FTP server address. You can find this in your hosting control panel (cPanel, Plesk, etc.)" style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[host]" value="{{@gs()->ftp->host}}" placeholder="ftp.yourdomain.com" required>
                                    <small class="text-muted">@lang('Example: ftp.yourdomain.com or 192.168.1.1')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('Username')
                                        <i class="fas fa-info-circle" title="Your FTP login username. Usually provided by your hosting provider." style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[username]" value="{{@gs()->ftp->username}}" placeholder="ftpuser" required>
                                    <small class="text-muted">@lang('Check your hosting email for credentials')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('Password')
                                        <i class="fas fa-info-circle" title="Your FTP login password. Should be provided by your hosting provider in a welcome email." style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[password]" value="{{@gs()->ftp->password}}" placeholder="••••••••" required>
                                    <small class="text-muted">@lang('Keep this secure and do not share')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('Port')
                                        <i class="fas fa-info-circle" title="Standard FTP uses port 21. SFTP (secure) uses port 22. Check with your hosting provider if unsure." style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[port]" value="{{@gs()->ftp->port}}" placeholder="21" required>
                                    <small class="text-muted">@lang('Usually 21 for FTP or 22 for SFTP')</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">
                                        @lang('Root Folder')
                                        <i class="fas fa-info-circle" title="The folder path where your website files are stored. Often /public_html, /www, or /httpdocs. Check in your hosting control panel." style="cursor: help; color: #6c757d;"></i>
                                    </label>
                                    <input class="form-control form-control-lg" type="text" name="ftp[root]" value="{{@gs()->ftp->root}}" placeholder="/public_html" required>
                                    <small class="text-muted">@lang('Common paths: /public_html, /www, /httpdocs')</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <h6 class="mb-2"><i class="fas fa-lightbulb"></i> @lang('How to find FTP credentials')</h6>
                            <ul class="mb-0 small">
                                <li>@lang('Check your hosting provider\'s welcome email or billing account')</li>
                                <li>@lang('Login to your hosting control panel (cPanel, Plesk, etc.)')</li>
                                <li>@lang('Look for "FTP Accounts" or "File Manager" section')</li>
                                <li>@lang('If you don\'t see these details, contact your hosting support team')</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

