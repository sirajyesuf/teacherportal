<tr>
    <th colspan="3">
         <u>Attendance Record</u>
    </th>
</tr>
<tr>
    <th>
        Student Name:
    </th>
    <th colspan="2">
         {{$student}}
    </th>
</tr>
@foreach ($exportData as $packageData)
    <table>
        <thead>
            
            <tr>
                <th colspan="3">Package: {{ $packageData['package'] }}</th>
            </tr>
            <tr>
                <th style="width: 150px;">Date</th>
                <th style="width: 150px;">Lesson duration</th>
                <th style="width: 200px;">Program</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packageData['data'] as $row)
                <tr>
                    <td>{{ profileDateFormate($row['Date']) }}</td>
                    <td>{{ $row['Lesson duration'] }}</td>
                    <td>{{ $row['Program'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td>
                    Hours Completed: {{$packageData['completedHours']}}
                </td>
                <td>                    
                </td>
                <td>
                    Hours Remaining: {{$packageData['remainingHours']}}
                </td>
            </tr>
        </tbody>
    </table>
@endforeach
