@@extends('layouts.home')

@@section('content')
<div class="panel panel-default">
<div class="panel-heading">
    记录管理
</div>
<div class="panel-body">
    <div>
        <a href="#myModal" class="btn btn-link" @@click="addRow">添加新记录</a>
    </div>
    <table class="table">
        <tr>
            <td>序号</td>
            @foreach($fields as $k => $v)
            <td>{{$v}}</td>
            @endforeach
            <td>操作</td>
        </tr>
        <tr v-for="(item, index) in rows">
            <td>@@{{ index + 1 }}</td>
            <?php
            foreach ($fields as $k => $v) {
                echo "<td>@{{item.$k}}</td>\n";
            }
            ?>
            <td>
                <a :href="'/{{$className}}s/' + item.id + '/edit'" @@click.prevent="editRow(index)">编辑</a> ｜
                <a :href="'/{{$className}}s/' + item.id" @@click.prevent="delRow(index)">删除</a>
            </td>
        </tr>
    </table>
</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                    @@{{row.title}}
                </h4>
            </div>
            @foreach ($fields as $k => $v)
            <div class="modal-body text-center">
                <div class="form-group">
                    {{$v}}：<input type="text" v-model="row.{{$k}}" name="{{$k}}" />
                </div>
            </div>
            @endforeach
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" @@click="handleRow">
                    @@{{row.submit}}
                </button>
            </div>
        </div>
    </div>
</div>
@@endsection

@@section('script')
<script type="text/javascript">
var data = { 
    rows: @{!! $rows !!},
    row: {!! $fields_json !!}
};
var vm = new Vue({
    el: '#app',
    data: data,
    created: function() {
        
    },
    methods: {
        handleRow: function() {
            if (this.row.event == 'addRow') {
                // 添加数据
                this.$http.post('/{{$className}}s', {
                    @foreach ($fields as $k => $v){{$k}}: <?php echo "this.row.$k,\n"; ?>@endforeach
                }, {emulateJSON: true}).then(response => {
                    if (response.body == 'ok') {
                        // 更新数据
                        this.$http.get('/{{$className}}s/all').then(response => {
                            this.rows = response.data;
                        }, response => {
                            // error callback
                        });
                    }
                }, response => {
                    // error callback
                });
            } else {
                // 更新数据
                this.$http.put('/{{$className}}s/' + this.row.id, {
                    @foreach ($fields as $k => $v){{$k}}: <?php echo "this.row.$k,\n"; ?>@endforeach
                }, {emulateJSON: true}).then(function(response) {
                    if (response.body == 'ok') {
                        // 更新数据
                        this.rows[this.row.index] = this.row;
                    }
                });
            }
            // 隐藏模态框
            $('#myModal').modal('hide')
        },
        addRow: function() {
            this.row = {!! $fields_json !!};
            this.row.title = '添加新记录';
            this.row.submit = '添加';
            this.row.event = 'addRow';
            // 显示模态框
            $('#myModal').modal();
        },
        editRow: function(index) {
            this.row = this.rows[index];
            this.row.title = '编辑记录';
            this.row.submit = '编辑';
            this.row.event = 'doEditRow';
            this.row.index = index;
            // 显示模态框
            $('#myModal').modal();
        },
        delRow: function(index) {
            var data = this.rows;
            if (confirm('您确定要删除该条记录吗？')) {
                this.$http.delete('/{{$className}}s/' + data[index].id)
                    .then(function(response) {
                        if (response.body == 'ok') {
                            data.splice(index, 1);
                        }
                    });
            }
        }
    }
});
</script>
@@endsection