<template>
    <div class="container-fluid p-0 m-0">
        <div class="navbar row d-flex p-0 m-0 text-align-center justify-content-center text-center bg-primary">
            <div class="title col-md-3  m-0 p-0">
                <h1 class="pb-3">Daily Attendance Detail</h1>
            </div>
            <div class="date-start col-md-3 d-flex">
                <h4 class="ml-auto m-0 ">Time from = </h4>
                <div id="datepicker" class="input-group date mt-4" data-date-format="mm-dd-yyyy">
                    <input class="form-control" type="text" readonly />
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            </div>
            <div class="date-end col-md-3 d-flex">
                <h4 class="ml-auto m-0 ">To = </h4>
                <div id="datepicker1" class="input-group date mt-4" data-date-format="mm-dd-yyyy">
                    <input class="form-control" type="text" readonly />
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            </div>
            <div class="personal col-md-3 d-flex ">
                <h4 class="ml-5 m-0">Personal ID = </h4>
                <input class="ml-4 mb-1" type="text" placeholder="enter...">
            </div>
        </div>
        <div class="bs-example container-fluid" data-example-id="striped-table">
            <table  id="test" class="table table-striped table-bordered table-hover">
                    <tr>
                        <td colspan="2">Personal</td>
                        <td rowspan="2" class="top">Attendance Date</td>
                        <td rowspan="2" class="top">Week</td>
                        <td rowspan="2" class="top">Punch time</td>
                        <td rowspan="2" class="top">Punch count</td>
                        <td rowspan="2" class="top">Late Count</td>
                        <td rowspan="2" class="top">Late Minutes</td>
                        <td colspan="3">Time Detail</td>
                        <td colspan="2">Abnormal Status</td>
                    </tr>
                    <tr>
                        <td scope="row">ID</td>
                        <td>Name</td>
                        <td>Attend Time</td>
                        <td>Off Work Time</td>
                        <td>Working Time</td>
                        <td>Absent</td>
                        <td>Early leave</td>
                    </tr>
            </table>
        </div>
                   		
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>