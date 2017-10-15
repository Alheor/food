    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
        Не выбрано
    </button>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="manufacturer_tree"></div>
                    <script type="application/javascript">
                        var data = [
                            {
                                name: 'node1', id: 1,
                                children: [
                                    { name: 'child1', id: 2 },
                                    { name: 'child2', id: 3 }
                                ]
                            },
                            {
                                name: 'node2', id: 4,
                                children: [
                                    { name: 'child3', id: 5 }
                                ]
                            }
                        ];
                        $('#category_tree').tree({
                            data: data,
                            autoOpen: false,
                            dragAndDrop: false,
                            closedIcon: '+',
                            openedIcon:'-'
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>