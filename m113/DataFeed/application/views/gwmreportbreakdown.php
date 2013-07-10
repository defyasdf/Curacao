            	<div class="module_content">
                	
                    <div class="tab_container">
        					         <table class="tablesorter" cellspacing="0"> 
                                                <thead>
                                                    <tr>
                                                        <th>Item Description</th>
                                                        <th>Numbers (Qty) </th>
                                                        <th>Sub Total</th>
                                                        <th>Tax + Shipping</th>
                                                        <th>Discount (Only for Order Completed)</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <tr>
                                                    <td colspan="6">
                                                       <hr>
                                                    </td>
                                                </tr>	
                                                <tr>	
                                                    <td>
                                                         Add to cart

                                                    </td>
                                                    <td>
                                                        <?php echo $content['addtocart']['count']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['addtocartsubtotal']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['addtocartshiptax']?>
                                                    </td>
                                                    <td>
                                                        $0
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['addtocarttotal']?>
                                                    </td>
                                                </tr>
                                                
                                              <tr>	
                                                    <td>
                                                        Checkout

                                                    </td>
                                                    <td>
                                                        <?php echo $content['checkout']['count']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['checkout']['checkoutsubtotal']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['checkout']['checkoutshiptax']?>
                                                    </td>
                                                    <td>
                                                        $0
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['checkout']['checkouttotal']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>	
                                                    <td>
                                                         Overall Order

                                                    </td>
                                                    <td>
                                                        <?php echo $content['addtocart']['overallcount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['overallsubtotal']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['overallshippingtax']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['overalldiscount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['addtocart']['overalltotal']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="6">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                
                                                 <tr>	
                                                    <td>
                                                         Order With GWM Cookie Processing Order (Processing,Pending)

                                                    </td>
                                                    <td>
                                                        <?php echo $content['completedwithcookie']['noncancelcount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookiesubtotalnoncancel']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookieshiptaxnoncancel']?>
                                                    </td>
                                                    <td>
                                                         $<?php echo $content['completedwithcookie']['orderwithcookiediscountnoncancel']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookietotalnoncancel']?>
                                                    </td>
                                                </tr>
                                                <tr>	
                                                    <td>
                                                         Order With GWM Cookie Complete Order

                                                    </td>
                                                    <td>
                                                        <?php echo $content['completedwithcookie']['completecount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookiesubtotalcomplete']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookieshiptaxcomplete']?>
                                                    </td>
                                                    <td>
                                                         $<?php echo $content['completedwithcookie']['orderwithcookiediscountcomplete']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookietotalcomplete']?>
                                                    </td>
                                                </tr>
                                                 <tr>	
                                                    <td>
                                                         Order With GWM Cookie Cancel Order

                                                    </td>
                                                    <td>
                                                        <?php echo $content['completedwithcookie']['cancelcount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookiesubtotalcancel']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookieshiptaxcancel']?>
                                                    </td>
                                                    <td>
                                                         $<?php echo $content['completedwithcookie']['orderwithcookiediscountcancel']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookietotalcancel']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="6">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                <tr>	
                                                    <td>
                                                         Order With GWM Cookie

                                                    </td>
                                                    <td>
                                                        <?php echo $content['completedwithcookie']['count']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookiesubtotal']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookieshiptax']?>
                                                    </td>
                                                    <td>
                                                         $<?php echo $content['completedwithcookie']['orderwithcookiediscount']?>
                                                    </td>
                                                    <td>
                                                        $<?php echo $content['completedwithcookie']['orderwithcookietotal']?>
                                                    </td>
                                                </tr>
                                                
                                                
                                                <tr>
                                                    <td colspan="6">
                                                       <hr><hr />
                                                    </td>
                                                </tr>
                                                <tr>
                                                	 <td colspan="6">
                                                       <h4>GWM Marketing Toatal Amount: $<?php echo $content['gwmpayment']['gwmrevshareamt']?></h4>
                                                    </td>
                                                </tr>
                                                </tbody>
                                           </table>
                                    
                  </div>       
                
				</div>
