@extends('layouts.tapp')
@section('content')
<div class="col-9">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <h1>{{Str::upper($modelName)}}</h1>
  <table id={{$modelName}} class="table table-striped table-bordered">
    <thead>
      <tr>
        <!-- <th width="300">Race</th> -->
        <th>ACTIVE</th>
        <th>EMAIL</th>
        <td>USERID</td>
        <td>STABLEID</td>
        <th>FIRSTNAME</th>
        <th>LASTNAME</th>
        <th>MOBILENO</th>
        <th>BDAY</th>
        <th width="100" style="text-align:right">ACTIONS</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($profiles as $profile)
      <tr>
        <td class="text-center">
          {{$profile->isactive ?? 'N/A'}}
        </td>
        <td class="text-center"><strong>{{ $profile->email }}</strong></td>
        <td class="text-center"><strong>{{ $profile->userid }}</strong></td>
        <td class="text-center"><strong>{{ $profile->stableid }}</strong></td>
        <td class="text-center">
          {{$profile->fname ?? 'UNK'}}
        </td>
        <td class="text-center">
          {{$profile->lname ?? 'UNK'}}
        </td>
        <td class="text-center">
          {{$profile->mobileno ?? 'UNK'}}
        </td>
        <td class="text-center">
          {{date('Y-m-d', strtotime($profile->bday)) ?? 'UNK'}}
        </td>
        <td class="text-center">
          <div>
            <a id="select-data" href={{($profile->stableid == "E0000014" ? 'AdminUserID|'.$profile->userid.'|'.$profile->userid :
              'StableID|'.$profile->stableid.'|'.$profile->userid )}} class="btn btn-main">SELECT</a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="entries py-5">
    <div class="row entry">
      <div class="col">
        <div class="form-group mb-3">
          <select class="horse-select select-2-basic col-12"style="height: 50px;"></select>
        </div>
      </div>
      <div class="col">

        <div class="form-group mb-3">
          <select class="rider-select select-2-basic col-12" style="height: 50px;"></select>
        </div>
      </div>
      <div class="col">

        <div class="form-group mb-3">
          <select class="event-select select-2-basic col-12" style="height: 50px;"></select>
        </div>
      </div>
    </div>
  </div>
  <div class="entries">
    <div class="row entry">
      <div class="col">
        <div class="form-group mb-3">
          <a id="entry-submit" class="btn btn-main col-12" href="submitentry/add">SUBMIT</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    let uid = 0;
    $('.event-select.select-2-basic').select2({
        ajax: {
          url: 'https://registration.eiev-app.ae/api/ajax/searchevent',
          dataType: 'json',
          type: 'GET',
          processResults: function (data) {
            return {
              "results": $.map(data.events.data, function (obj) {
                obj.id = obj.raceid || obj.raceid;
                obj.text = obj.text ||
                  `${obj.racename}" - ${obj.racelocation} | ${obj.racefromdate}`;

                return obj;
              })
            }
          }
        }
      });
    $('#submitentry').DataTable();

    $(document).on('click', '#entry-submit', function (e) {
      
      e.preventDefault();
      let self = this;
      let href = $(self).attr('href');
      const eid = $('.event-select.select-2-basic').val();
      const hid = $('.horse-select.select-2-basic').val();
      const rid = $('.rider-select.select-2-basic').val();
      if(uid >0){
        href = `https://registration.eiev-app.ae/${href}?params[EventID]=${eid}&params[HorseID]=${hid}&params[RiderID]=${rid}&params[UserID]=${uid}`;
        window.location.href = href
      }
    });

    $(document).on('click', '#select-data', function (e) {
      e.preventDefault();
      let self = this;
      const href = $(self).attr('href');
      const ddata = href.split("|");
      const dkey = ddata[0];
      const dval = ddata[1];
      uid = ddata[2];
      $('.horse-select.select-2-basic').val(null).trigger('change');
      $('.rider-select.select-2-basic').val(null).trigger('change');
      $('.horse-select.select-2-basic').select2({
        ajax: {
          url: 'https://registration.eiev-app.ae/api/ajax/searchhorse',
          dataType: 'json',
          type: 'GET',
          data: function (params) {
            let query = {
              SearchName: params.term,
              dkey: dval
            }
            return query;
          },
          processResults: function (data) {
            return {
              "results": $.map(data.horses.data, function (obj) {
                obj.id = obj.horseid || obj.horseid;
                obj.text = obj.text ||
                  `${obj.horseid}">${obj.name} / ${obj.nfregistration} / ${obj.gender} / ${obj.color}`;

                return obj;
              })
            }
          }
        }
      });
      $('.rider-select.select-2-basic').select2({
        ajax: {
          url: 'https://registration.eiev-app.ae/api/ajax/searchrider',
          dataType: 'json',
          type: 'GET',
          data: function (params) {
            let query = {
              SearchFullName: params.term,
              dkey: dval
            }
            return query;
          },
          processResults: function (data) {
            return {
              "results": $.map(data.riders.data, function (obj) {
                obj.id = obj.riderid || obj.riderid;
                obj.text = obj.text ||
                  `${obj.firstx0020name} ${obj.familyx0020name} (${obj.stable}) ${obj.nfx0020license} / ${obj.feix0020reg} / ${obj.countryshort}`; // replace pk with your identifier
                return obj;
              })
            }
          }
        }
      });

    });

  });
</script>
@endsection