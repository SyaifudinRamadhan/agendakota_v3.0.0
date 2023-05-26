<link rel="stylesheet" type="text/css" href="{{ asset('css/user/cartTicket.css') }}">

<form action="{{ route('user.checkout') }}" method="POST">
	{{csrf_field()}}
	
	<div class="row mb-2 pb-1">
		<?php 
		use Carbon\Carbon;
    	use App\Models\Invitation;
    	use App\Models\Purchase;
    	$totalPay = 0;
		$counter = 0;
		foreach ($carts as $cart) : 
		?>
			<div class="col-12">
				<div class="w-100">
					<div class="row row-no-margin">
						<div class="ticket-2 col-lg-9 strip text-left">
							<div>
								<h1 class="ticket-name mt-1">
	                                <b><?= $cart->ticket->name ?></b>
	                            </h1>
								<?php 
									if($cart->ticket->deleted == 1){
										echo("<p class='text-danger'>Ticket sudah dihapus</p>");
									}

									$limitBuy = $cart->ticket->quantity;
									$limitByUser = 5-count(Purchase::where('send_from', $myData->id)->where('ticket_id',$cart->ticket->id)->get());
									if($limitBuy > 5){
										$limitBuy = $limitByUser;
									}else{
										if($limitBuy > $limitByUser){
											$limitBuy = $limitByUser;
										}
									}
								?>
								
	                            <p class="fs-16 mb-0">
	                            	<i class="fa bi bi-star mr-3"></i><?= $cart->ticket->session->event->name ?>
	                            </p>
	                            <input type="hidden" id="<?= 'limiter'.$cart->id ?>" value="<?php echo($limitBuy) ?>">
	                           <?php if($limitBuy > 0) : ?>

	                            <p class="fs-17 float-right text-right">
	                            	<?php if($limitBuy < 5){

	                            		echo('Tersisa '.$limitBuy.' Ticket'.'<br>');

	                            	} ?>

	                           		<?php if($cart->ticket->deleted == 0){ ?>
										<span class="btn btn-outline-primer fs-21 teks-primer ml-2" id="<?= $cart->id ?>" onclick="minCount(this)">-</span>
										<label id="<?= 'countTicket'.$cart->id ?>">1</label>
										<span class="btn btn-outline-primer fs-21 teks-primer mr-2" id="<?= $cart->id ?>" onclick="addCount(this)">+</span>
										
									<?php } ?>
	                           	</p>
	                           <?php else: ?>
	                           	<?php if($limitByUser == 0): ?>
									<p class="float-right text-right fs-16 text-danger">Kamu sudah membeli lima (max 5)</p>
								<?php else: ?>
									<p class="float-right text-right fs-16 text-danger">Ticket sudah habis</p>
								<?php endif; ?>
	                           <?php endif; ?>
	                           	
	                           	
	                           	<p class="fs-14">
	                           		<i class="fas fa-calendar mr-3"></i>
	                                <?= Carbon::parse($cart->ticket->session->start_date)->format('d M,') ?> <?= Carbon::parse($cart->ticket->session->start_time)->format('H:i') ?> WIB -
	                                <?= Carbon::parse($cart->ticket->session->end_date)->format('d M,') ?> <?= Carbon::parse($cart->ticket->session->end_time)->format('H:i') ?> WIB
	                           	</p>
	                           	<p class="teks-primer fs-17 mb-0">
	                           		{{-- <b><?php //echo $cart->ticket->price == 0 ? 'Gratis' : 'Rp.'.$cart->ticket->price.',00' ?></b> --}}
									{{-- <input type="hidden" name="custom_price_ids[]" value="{{ $cart->ticket->id }}"> --}}
									<?php if($cart->ticket->price == 0){ ?>
										<b>Gratis</b>
										<input type="hidden" name="custom_prices[]" value="{{ $cart->ticket->price }}">
									<?php } else { ?>
										<?php if($cart->ticket->type_price == 1){ ?>
											@currencyEncode($cart->ticket->price)
											<input type="hidden" name="custom_prices[]" value="{{ $cart->ticket->price }}">
										<?php }else if($cart->ticket->type_price == 2) { ?>
											<p class="mb-0 mt-0 teks-primer" style="font-size:12px !important;margin-top:10px;">
                                                Bayar Sesukamu Minimal :
												{{-- Function JS setCustomPrice() digunakan untuk mengubah input hidden dari price ticket dan mengubah total price --}}
												<input type="number" class="box no-bg" name="custom_prices[]" value="{{ $cart->ticket->price }}" oninput="setCustomPrice(this, {{ $cart }});">
                                            </p>
										<?php } ?>
									<?php } ?>
	                           	</p>
	                           	
	                         </div>
						</div>
						<div class="ticket-3 col-lg-3 p-5 strip-2">
							<label class="no-pd text-center w-100">
								<h4 style="font-size: 18px">
									<?= $cart->ticket->session->event->name ?>
									<br>
									<p class="fs-14 mt-3">
										<?= Carbon::parse($cart->ticket->session->event->start_date)->format('d M Y') ?> -
		                                <?= Carbon::parse($cart->ticket->session->event->end_date)->format('d M Y') ?>
									</p>
								</h4>
								
							</label>
						</div>
					</div>
				</div>
			</div>

			<?php if($cart->ticket->deleted == 0){ ?>
				<input type="hidden" name="eventID[]" value="<?= $cart->ticket->session->event->id ?>">
				<input type="hidden" name="ticketID[]" value="<?= $cart->ticket->id ?>">
				<input type="hidden" name="cartID[]" value="<?= $cart->id ?>">
				<?php if((5-count(Purchase::where('send_from', $myData->id)->where('ticket_id',$cart->ticket->id)->get())) > 0) : ?>
					<input type="hidden" name="quantity[]" value="1" id="<?= 'inBuy'.$cart->id ?>">
				<?php else: ?>
					<input type="hidden" name="quantity[]" value="0" id="<?= 'inBuy'.$cart->id ?>">
				<?php endif; ?>
				<input id="<?= 'price'.$cart->id ?>" type="hidden" name="price[]" value="<?= $cart->ticket->price ?>">

				<?php if($counter < (count($carts)-1)) : ?>
					<hr class="mt-3 mb-3">
				<?php endif; ?>

				<?php
				if((5-count(Purchase::where('send_from', $myData->id)->where('ticket_id',$cart->ticket->id)->get())) > 0){
					$totalPay += $cart->ticket->price; 
				}
					$counter++;
				?>
			<?php } ?>
		<?php endforeach; ?>

	</div>
	<div class="row mt-2 pt-1">
		<div class="col-md-12 text-right pr-4">
			<p class="fs-21 font-weight-bold text-right mt-3" id="totalPay">
				<?= 'Total Pay : '.$totalPay ?>
			</p>
			<input id="totalPayInput" type="hidden" name="totalPay" value="<?= $totalPay ?>">
		</div>
	</div>
	<div class="row pb-5">
		<div class="col-md-3 text-left">
			<button type="submit" class="btn bg-primer w-100 mb-2" name="cancel" value="1">
				Cancel
			</button>
		</div>
		<div class="col-md-6"></div>
		<div class="col-md-3">
			<button type="submit" class="btn bg-primer w-100 mb-2" name="submit">
				Pay Now
			</button>
		</div>
	</div>

</form>

<script src="{{ asset('js/user/ticketCart.js') }}"></script>