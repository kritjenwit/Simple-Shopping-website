<!-- <div class="container"> -->
    <div class="row" style="margin-top:40px">
        <div class="col-md-6" ng-controller="stockController">
            <h6 class="text-center">Shopping List</h6>
            <table class="table" id="stock-table"> 
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>In Stock</th>
                        <th>Number</th>
                        <th>Add to cart</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in items">
                        <td><span>{{item.itemcd}}</span></td>
                        <td><span>{{item.itemnm}}</span></td>
                        <td><span ng-bind="stock={{item.stock}}" ng-show="stock!=0"></span><span ng-if="stock==0">Out of Stock</span></td>
                        <td><input type="number" ng-model="number" class="form-control" ng-class="{readonly: item.stock==0}" name="" id="" min=0 max="{{item.stock}}" ng-init="number=0"></td>
                        <td><button class="btn btn-primary btn-block" ng-class="{disabled : item.stock==0}" ng-click="addToCart(item)">Add to cart</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6" ng-controller="cartController">
            <h6 id="text-cart" class="text-center block">Cart List</h6>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Number</th>
                        <th>Action</th>
                        <!-- <th>Buy</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in items">
                        <td>{{item.itemcd}}</td>
                        <td>{{item.itemnm}}</td>
                        <td>{{item.number}}</td>
                        <td><button class="btn btn-danger" ng-click="remove(item)">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button ng-show='items.length!=0' class="btn" ng-click="proceed()">Proceed</button>
        </div>
    </div>
<!-- </div> -->


    
    
</div>



<script>

    function compare(a,b) {
        if (a.id < b.id)
            return -1;
        if (a.id > b.id)
            return 1;
        return 0;
    }

        var config = {
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }
    var db_url = window.location.pathname + 'services/';
    angular.module('myApp',[])
        .controller('stockController', function ($scope,$http,stockService,cartService){
            var stock = stockService.GetStock();
            stock.then(function (response){
                if(response.data.status === 1){
                    $scope.items = response.data.msg;
                }
            })

            $scope.addToCart = function (item){
                var pos = Number(item.itemcd);
                var input = $('input:eq('+(pos-1)+')');
                var number = {
                    number: input.val() != '' ? input.val() : '0',
                    pos:pos
                };
         
                if(number.number != '0'){
                    var text = $('input:eq('+(pos-1)+')').parent().prev().text();
                    console.log(text);
                    var select_prev = Number(text);
                    if(input.val() < select_prev + 1){
                        cartService.addToCart($.extend(item,number));
                    }else{
                        alert('Number to buy is greater than stock');
                    }
                    // var new_val = select_prev - input.val();
                    // $('input:eq('+(pos-1)+')').parent().prev().text(''+new_val+'');
                    // input.prop('max',new_val);
                    // $('button.btn.btn-primary:eq(' + (pos-1) + ')').attr('disabled','disable');
                    // console.log( $('button.btn.btn-primary:eq(' + (pos-1) + ')'));
                    
                }else{
                    alert('Please give a quantity');
                }
                
            }
        })  
        .controller('cartController', function (cartService,$scope){
            $scope.items = cartService.getStock();
            $scope.remove = function (){
                cartService.removeStock(this.$index);
                console.log(this);
            }

            $scope.proceed = function (){
                cartService.proceed().then(function (response){
                    if(response.data.status === 1){
                        alert(response.data.msg);
                        window.location.href = "";
                    }else{
                        alert(response.data.msg);
                    }
                });
            }
        })
        .factory('stockService', function ($http){
           
            return {
                GetStock: function (){
                    return $http.post(db_url+'Stocks/GetStock',config)
                },
            }
        })
        .factory('cartService', function ($http){
            var stock = [];
            var res;
            var BreakException =[] ;
            var id = [];
            function inArray(needle, haystack) {
                var length = haystack.length;
                for(var i = 0; i < length; i++) {
                    if(haystack[i] == needle) return true;
                }
                return false;
            }
            return {
                addToCart : function (item){
                    if(stock.length == 0){
                        stock.push(item);
                        stock.forEach(function (e){
                            id.push(e.itemcd);
                        })
                        console.log('Add first id');
                    }else{
                        if(inArray(item.itemcd,id)){
                            console.log('found');

                        }else{
                            console.log('Not found');
                            stock.push(item);
                            id.push(item.itemcd);
                        }
                    }
                },
                getStock : function (){
                    return stock;
                },
                removeStock : function (index){
                    stock.splice(index, 1);
                },
                proceed: function (){
                    data = $.param({
                        item:stock
                    });
                    return $http.post(db_url+'Stocks/Proceed',data,config)
                }
            }
        })
</script>