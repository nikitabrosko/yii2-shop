<?php

/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */

use frontend\assets\MagnificPopupAsset;

$this->title = 'HP LP3065';

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

MagnificPopupAsset::register($this);
?>

<div class="row">
    <div id="content" class="col">
        <div class="row mb-3">
            <div class="col-sm">
                <div class="image magnific-popup">
                    <? foreach ($product->photos as $i => $photo): ?>
                        <? if ($i == 1): ?>
                        <div>
                        <? endif; ?>

                        <? if ($i == 0): ?>
                            <a href="<?= $photo->getUploadedFileUrl('file') ?>" title="<?= $product->name ?>">
                                <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>"
                                     title="<?= $product->name ?>"
                                     alt="<?= $product->name ?>"
                                     class="img-thumbnail mb-3" />
                            </a>
                        <? else: ?>
                            <a href="<?= $photo->getUploadedFileUrl('file') ?>" title="<?= $product->name ?>">
                                <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_additional') ?>"
                                     title="<?= $product->name ?>"
                                     alt="<?= $product->name ?>"
                                     class="img-thumbnail" />
                            </a>&nbsp;
                        <? endif; ?>

                        <? if ($i == count($product->photos) - 1): ?>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>
            <div class="col-sm">
                <h1>Apple Cinema 30&quot;</h1>
                <ul class="list-unstyled">
                    <li>Brand: <a href="https://demo.opencart.com/index.php?route=product/manufacturer|info&amp;language=en-gb&amp;manufacturer_id=8">Apple</a></li>
                    <li>Product Code: Product 15</li>
                    <li>Reward Points: 100</li>
                    <li>Availability: In Stock</li>
                </ul>
                <ul class="list-unstyled">
                    <li><span class="price-old">122.09€</span></li>
                    <li><h2><span class="price-new">110.08€</span></h2></li>
                    <li>Ex Tax: 90.06€</li>
                    <li>Price in reward points: 400</li>
                    <li>
                        <hr>
                    </li>
                    <li>10 or more 107.68€</li>
                    <li>20 or more 94.47€</li>
                    <li>30 or more 81.26€</li>
                </ul>
                <form method="post" data-oc-toggle="ajax">
                    <div class="btn-group">
                        <button type="submit" formaction="https://demo.opencart.com/index.php?route=account/wishlist|add&amp;language=en-gb" data-bs-toggle="tooltip" class="btn btn-light" title="Add to Wish List" onclick="wishlist.add('42');"><i class="fas fa-heart"></i></button>
                        <button type="submit" formaction="https://demo.opencart.com/index.php?route=product/compare|add&amp;language=en-gb" data-bs-toggle="tooltip" class="btn btn-light" title="Compare this Product" onclick="compare.add('42');"><i class="fas fa-exchange-alt"></i></button>
                    </div>
                    <input type="hidden" name="product_id" value="42" />
                </form>
                <br />
                <div id="product">
                    <form id="form-product">
                        <hr>
                        <h3>Available Options</h3>
                        <div>
                            <div class="mb-3 required">
                                <label class="form-label">Radio</label>
                                <div id="input-option-218">
                                    <div class="form-check">
                                        <input type="radio" name="option[218]" value="5" id="input-option-value-5" class="form-check-input" /> <label for="input-option-value-5" class="form-check-label"> Small
                                            (+14.01€)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="option[218]" value="6" id="input-option-value-6" class="form-check-input" /> <label for="input-option-value-6" class="form-check-label"> Medium
                                            (+26.02€)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="option[218]" value="7" id="input-option-value-7" class="form-check-input" /> <label for="input-option-value-7" class="form-check-label"> Large
                                            (+38.03€)
                                        </label>
                                    </div>
                                </div>
                                <div id="error-option-218" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label class="form-label">Checkbox</label>
                                <div id="input-option-223">
                                    <div class="form-check">
                                        <input type="checkbox" name="option[223][]" value="8" id="input-option-value-8" class="form-check-input" /> <label for="input-option-value-8" class="form-check-label">
                                            Checkbox 1
                                            (+14.01€)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="option[223][]" value="9" id="input-option-value-9" class="form-check-input" /> <label for="input-option-value-9" class="form-check-label">
                                            Checkbox 2
                                            (+26.02€)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="option[223][]" value="10" id="input-option-value-10" class="form-check-input" /> <label for="input-option-value-10" class="form-check-label">
                                            Checkbox 3
                                            (+38.03€)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="option[223][]" value="11" id="input-option-value-11" class="form-check-input" /> <label for="input-option-value-11" class="form-check-label">
                                            Checkbox 4
                                            (+50.04€)
                                        </label>
                                    </div>
                                </div>
                                <div id="error-option-223" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-208" class="form-label">Text</label> <input type="text" name="option[208]" value="test" placeholder="Text" id="input-option-208" class="form-control" />
                                <div id="error-option-208" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-217" class="form-label">Select</label> <select name="option[217]" id="input-option-217" class="form-select">
                                    <option value=""> --- Please Select --- </option>
                                    <option value="4">Red
                                        (+6.80€)
                                    </option>
                                    <option value="3">Blue
                                        (+5.60€)
                                    </option>
                                    <option value="1">Green
                                        (+3.20€)
                                    </option>
                                    <option value="2">Yellow
                                        (+4.40€)
                                    </option>
                                </select>
                                <div id="error-option-217" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-209" class="form-label">Textarea</label> <textarea name="option[209]" rows="5" placeholder="Textarea" id="input-option-209" class="form-control"></textarea>
                                <div id="error-option-209" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="button-upload-222" class="form-label">File</label>
                                <div>
                                    <button type="button" id="button-upload-222" data-oc-toggle="upload" data-oc-size-max="20971520" data-oc-size-error="Warning: The uploaded file exceeds the 20mb max file size!" data-oc-url="https://demo.opencart.com/index.php?route=tool/upload&amp;language=en-gb" data-oc-target="#input-option-222" class="btn btn-light btn-block"><i class="fas fa-upload"></i> Upload File</button>
                                    <input type="hidden" name="option[222]" value="" id="input-option-222" />
                                </div>
                                <div id="error-option-222" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-219" class="form-label">Date</label>
                                <div class="input-group">
                                    <input type="text" name="option[219]" value="2011-02-20" id="input-option-219" class="form-control date" />
                                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                </div>
                                <div id="error-option-219" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-221" class="form-label">Time</label>
                                <div class="input-group">
                                    <input type="text" name="option[221]" value="22:25" id="input-option-221" class="form-control time" />
                                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                </div>
                                <div id="error-option-221" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 required">
                                <label for="input-option-220" class="form-label">Date &amp; Time</label>
                                <div class="input-group">
                                    <input type="text" name="option[220]" value="2011-02-20 22:25" id="input-option-220" class="form-control datetime" />
                                    <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                </div>
                                <div id="error-option-220" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="input-quantity" class="form-label">Qty</label> <input type="text" name="quantity" value="2" size="2" id="input-quantity" class="form-control" /> <input type="hidden" name="product_id" value="42" id="input-product-id" />
                                <div id="error-quantity" class="form-text"></div>
                                <br />
                                <button type="submit" id="button-cart" class="btn btn-primary btn-lg btn-block">Add to Cart</button>
                            </div>
                            <div class="alert alert-info"><i class="fas fa-info-circle"></i> This product has a minimum quantity of 2</div>
                        </div>
                        <div class="rating">
                            <p><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span><span class="fas fa-stack"><i class="far fa-star fa-stack-1x"></i></span> <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">0 reviews</a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">Write a review</a></p>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#tab-description" id="description-tab" class="nav-link active" data-bs-toggle="tab">Description</a></li>
                <li class="nav-item"><a href="#tab-specification" id="specification-tab" class="nav-link" data-bs-toggle="tab">Specification</a></li>
                <li class="nav-item"><a href="#tab-review" id="review-tab" class="nav-link" data-bs-toggle="tab">Reviews (0)</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-description" class="tab-pane fade show active mb-4" role="tabpanel" aria-labelledby="description-tab"><p>
                        <font face="helvetica,geneva,arial" size="2"><font face="Helvetica" size="2">The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there's no limit to what you can achieve. <br>
                                <br>
                            </font><font face="Helvetica" size="2">The Cinema HD features an active-matrix liquid crystal display that produces flicker-free images that deliver twice the brightness, twice the sharpness and twice the contrast ratio of a typical CRT display. Unlike other flat panels, it's designed with a pure digital interface to deliver distortion-free images that never need adjusting. With over 4 million digital pixels, the display is uniquely suited for scientific and technical applications such as visualizing molecular structures or analyzing geological data. <br>
                                <br>
                            </font><font face="Helvetica" size="2">Offering accurate, brilliant color performance, the Cinema HD delivers up to 16.7 million colors across a wide gamut allowing you to see subtle nuances between colors from soft pastels to rich jewel tones. A wide viewing angle ensures uniform color from edge to edge. Apple's ColorSync technology allows you to create custom profiles to maintain consistent color onscreen and in print. The result: You can confidently use this display in all your color-critical applications. <br>
                                <br>
                            </font><font face="Helvetica" size="2">Housed in a new aluminum design, the display has a very thin bezel that enhances visual accuracy. Each display features two FireWire 400 ports and two USB 2.0 ports, making attachment of desktop peripherals, such as iSight, iPod, digital and still cameras, hard drives, printers and scanners, even more accessible and convenient. Taking advantage of the much thinner and lighter footprint of an LCD, the new displays support the VESA (Video Electronics Standards Association) mounting interface standard. Customers with the optional Cinema Display VESA Mount Adapter kit gain the flexibility to mount their display in locations most appropriate for their work environment. <br>
                                <br>
                            </font><font face="Helvetica" size="2">The Cinema HD features a single cable design with elegant breakout for the USB 2.0, FireWire 400 and a pure digital connection using the industry standard Digital Video Interface (DVI) interface. The DVI connection allows for a direct pure-digital connection.<br>
                            </font></font></p>
                    <h3>
                        Features:</h3>
                    <p>
                        Unrivaled display performance</p>
                    <ul>
                        <li>
                            30-inch (viewable) active-matrix liquid crystal display provides breathtaking image quality and vivid, richly saturated color.</li>
                        <li>
                            Support for 2560-by-1600 pixel resolution for display of high definition still and video imagery.</li>
                        <li>
                            Wide-format design for simultaneous display of two full pages of text and graphics.</li>
                        <li>
                            Industry standard DVI connector for direct attachment to Mac- and Windows-based desktops and notebooks</li>
                        <li>
                            Incredibly wide (170 degree) horizontal and vertical viewing angle for maximum visibility and color performance.</li>
                        <li>
                            Lightning-fast pixel response for full-motion digital video playback.</li>
                        <li>
                            Support for 16.7 million saturated colors, for use in all graphics-intensive applications.</li>
                    </ul>
                    <p>
                        Simple setup and operation</p>
                    <ul>
                        <li>
                            Single cable with elegant breakout for connection to DVI, USB and FireWire ports</li>
                        <li>
                            Built-in two-port USB 2.0 hub for easy connection of desktop peripheral devices.</li>
                        <li>
                            Two FireWire 400 ports to support iSight and other desktop peripherals</li>
                    </ul>
                    <p>
                        Sleek, elegant design</p>
                    <ul>
                        <li>
                            Huge virtual workspace, very small footprint.</li>
                        <li>
                            Narrow Bezel design to minimize visual impact of using dual displays</li>
                        <li>
                            Unique hinge design for effortless adjustment</li>
                        <li>
                            Support for VESA mounting solutions (Apple Cinema Display VESA Mount Adapter sold separately)</li>
                    </ul>
                    <h3>
                        Technical specifications</h3>
                    <p>
                        <b>Screen size (diagonal viewable image size)</b></p>
                    <ul>
                        <li>
                            Apple Cinema HD Display: 30 inches (29.7-inch viewable)</li>
                    </ul>
                    <p>
                        <b>Screen type</b></p>
                    <ul>
                        <li>
                            Thin film transistor (TFT) active-matrix liquid crystal display (AMLCD)</li>
                    </ul>
                    <p>
                        <b>Resolutions</b></p>
                    <ul>
                        <li>
                            2560 x 1600 pixels (optimum resolution)</li>
                        <li>
                            2048 x 1280</li>
                        <li>
                            1920 x 1200</li>
                        <li>
                            1280 x 800</li>
                        <li>
                            1024 x 640</li>
                    </ul>
                    <p>
                        <b>Display colors (maximum)</b></p>
                    <ul>
                        <li>
                            16.7 million</li>
                    </ul>
                    <p>
                        <b>Viewing angle (typical)</b></p>
                    <ul>
                        <li>
                            170° horizontal; 170° vertical</li>
                    </ul>
                    <p>
                        <b>Brightness (typical)</b></p>
                    <ul>
                        <li>
                            30-inch Cinema HD Display: 400 cd/m2</li>
                    </ul>
                    <p>
                        <b>Contrast ratio (typical)</b></p>
                    <ul>
                        <li>
                            700:1</li>
                    </ul>
                    <p>
                        <b>Response time (typical)</b></p>
                    <ul>
                        <li>
                            16 ms</li>
                    </ul>
                    <p>
                        <b>Pixel pitch</b></p>
                    <ul>
                        <li>
                            30-inch Cinema HD Display: 0.250 mm</li>
                    </ul>
                    <p>
                        <b>Screen treatment</b></p>
                    <ul>
                        <li>
                            Antiglare hardcoat</li>
                    </ul>
                    <p>
                        <b>User controls (hardware and software)</b></p>
                    <ul>
                        <li>
                            Display Power,</li>
                        <li>
                            System sleep, wake</li>
                        <li>
                            Brightness</li>
                        <li>
                            Monitor tilt</li>
                    </ul>
                    <p>
                        <b>Connectors and cables</b><br>
                        Cable</p>
                    <ul>
                        <li>
                            DVI (Digital Visual Interface)</li>
                        <li>
                            FireWire 400</li>
                        <li>
                            USB 2.0</li>
                        <li>
                            DC power (24 V)</li>
                    </ul>
                    <p>
                        Connectors</p>
                    <ul>
                        <li>
                            Two-port, self-powered USB 2.0 hub</li>
                        <li>
                            Two FireWire 400 ports</li>
                        <li>
                            Kensington security port</li>
                    </ul>
                    <p>
                        <b>VESA mount adapter</b><br>
                        Requires optional Cinema Display VESA Mount Adapter (M9649G/A)</p>
                    <ul>
                        <li>
                            Compatible with VESA FDMI (MIS-D, 100, C) compliant mounting solutions</li>
                    </ul>
                    <p>
                        <b>Electrical requirements</b></p>
                    <ul>
                        <li>
                            Input voltage: 100-240 VAC 50-60Hz</li>
                        <li>
                            Maximum power when operating: 150W</li>
                        <li>
                            Energy saver mode: 3W or less</li>
                    </ul>
                    <p>
                        <b>Environmental requirements</b></p>
                    <ul>
                        <li>
                            Operating temperature: 50° to 95° F (10° to 35° C)</li>
                        <li>
                            Storage temperature: -40° to 116° F (-40° to 47° C)</li>
                        <li>
                            Operating humidity: 20% to 80% noncondensing</li>
                        <li>
                            Maximum operating altitude: 10,000 feet</li>
                    </ul>
                    <p>
                        <b>Agency approvals</b></p>
                    <ul>
                        <li>
                            FCC Part 15 Class B</li>
                        <li>
                            EN55022 Class B</li>
                        <li>
                            EN55024</li>
                        <li>
                            VCCI Class B</li>
                        <li>
                            AS/NZS 3548 Class B</li>
                        <li>
                            CNS 13438 Class B</li>
                        <li>
                            ICES-003 Class B</li>
                        <li>
                            ISO 13406 part 2</li>
                        <li>
                            MPR II</li>
                        <li>
                            IEC 60950</li>
                        <li>
                            UL 60950</li>
                        <li>
                            CSA 60950</li>
                        <li>
                            EN60950</li>
                        <li>
                            ENERGY STAR</li>
                        <li>
                            TCO '03</li>
                    </ul>
                    <p>
                        <b>Size and weight</b><br>
                        30-inch Apple Cinema HD Display</p>
                    <ul>
                        <li>
                            Height: 21.3 inches (54.3 cm)</li>
                        <li>
                            Width: 27.2 inches (68.8 cm)</li>
                        <li>
                            Depth: 8.46 inches (21.5 cm)</li>
                        <li>
                            Weight: 27.5 pounds (12.5 kg)</li>
                    </ul>
                    <p>
                        <b>System Requirements</b></p>
                    <ul>
                        <li>
                            Mac Pro, all graphic options</li>
                        <li>
                            MacBook Pro</li>
                        <li>
                            Power Mac G5 (PCI-X) with ATI Radeon 9650 or better or NVIDIA GeForce 6800 GT DDL or better</li>
                        <li>
                            Power Mac G5 (PCI Express), all graphics options</li>
                        <li>
                            PowerBook G4 with dual-link DVI support</li>
                        <li>
                            Windows PC and graphics card that supports DVI ports with dual-link digital bandwidth and VESA DDC standard for plug-and-play setup</li>
                    </ul>
                </div>
                <div id="tab-specification" class="tab-pane fade" role="tabpanel" aria-labelledby="specification-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td colspan="2"><strong>Processor</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Clockspeed</td>
                                <td>100mhz</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab-review" class="tab-pane fade">
                    <form id="form-review">
                        <div id="review"></div>
                        <h2>Write a review</h2>
                        <div class="mb-3 required">
                            <label for="input-name" class="form-label">Your Name</label> <input type="text" name="name" value="" id="input-name" class="form-control" />
                            <div id="error-name" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 required">
                            <label for="input-review" class="form-label">Your Review</label> <textarea name="text" rows="5" id="input-text" class="form-control"></textarea>
                            <div id="error-text" class="invalid-feedback"></div>
                            <div class="invalid-feedback"><span class="text-danger">Note:</span> HTML is not translated!</div>
                        </div>
                        <div class="row mb-3 required">
                            <label class="form-label">Rating</label>
                            <div id="input-rating">
                                Bad&nbsp;
                                <input type="radio" name="rating" value="1" class="form-check-input" />
                                &nbsp;
                                <input type="radio" name="rating" value="2" class="form-check-input" />
                                &nbsp;
                                <input type="radio" name="rating" value="3" class="form-check-input" />
                                &nbsp;
                                <input type="radio" name="rating" value="4" class="form-check-input" />
                                &nbsp;
                                <input type="radio" name="rating" value="5" class="form-check-input" />
                                &nbsp;Good
                            </div>
                            <div id="error-rating" class="invalid-feedback"></div>
                        </div>
                        <div class="d-inline-block pt-2 pd-2 w-100">
                            <div class="float-end">
                                <button type="button" id="button-review" class="btn btn-primary">Continue</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <h3>Related Products</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
            <div class="col"><form method="post" data-oc-toggle="ajax" data-oc-load="https://demo.opencart.com/index.php?route=common/cart|info&amp;language=en-gb" data-oc-target="#header-cart">
                    <div class="product-thumb">
                        <div class="image"><a href="https://demo.opencart.com/index.php?route=product/product&amp;language=en-gb&amp;product_id=40"><img src="https://demo.opencart.com/image/cache/catalog/demo/iphone_1-250x250.jpg" alt="iPhone" title="iPhone" class="img-fluid" /></a></div>
                        <div class="content">
                            <div class="description">
                                <h4><a href="https://demo.opencart.com/index.php?route=product/product&amp;language=en-gb&amp;product_id=40">iPhone</a></h4>
                                <p>iPhone is a revolutionary new mobile phone that allows you to make a call by simply tapping a name o..</p>
                                <div class="price">
                                    <span class="price-new">123.29€</span>
                                    <span class="price-tax">Ex Tax: 101.07€</span>
                                </div>
                            </div>
                            <div class="button-group">
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=checkout/cart|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Cart"><i class="fas fa-shopping-cart"></i></button>
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=account/wishlist|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Wish List"><i class="fas fa-heart"></i></button>
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=product/compare|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Compare this Product"><i class="fas fa-exchange-alt"></i></button>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="40" />
                        <input type="hidden" name="quantity" value="1" />
                    </div>
                </form>
            </div>
            <div class="col"><form method="post" data-oc-toggle="ajax" data-oc-load="https://demo.opencart.com/index.php?route=common/cart|info&amp;language=en-gb" data-oc-target="#header-cart">
                    <div class="product-thumb">
                        <div class="image"><a href="https://demo.opencart.com/index.php?route=product/product&amp;language=en-gb&amp;product_id=41"><img src="https://demo.opencart.com/image/cache/catalog/demo/imac_1-250x250.jpg" alt="iMac" title="iMac" class="img-fluid" /></a></div>
                        <div class="content">
                            <div class="description">
                                <h4><a href="https://demo.opencart.com/index.php?route=product/product&amp;language=en-gb&amp;product_id=41">iMac</a></h4>
                                <p>Just when you thought iMac had everything, now there´s even more. More powerful Intel Core 2 Duo pro..</p>
                                <div class="price">
                                    <span class="price-new">122.09€</span>
                                    <span class="price-tax">Ex Tax: 100.07€</span>
                                </div>
                            </div>
                            <div class="button-group">
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=checkout/cart|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Cart"><i class="fas fa-shopping-cart"></i></button>
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=account/wishlist|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Add to Wish List"><i class="fas fa-heart"></i></button>
                                <button type="submit" formaction="https://demo.opencart.com/index.php?route=product/compare|add&amp;language=en-gb" data-bs-toggle="tooltip" title="Compare this Product"><i class="fas fa-exchange-alt"></i></button>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="41" />
                        <input type="hidden" name="quantity" value="1" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--<script type="text/javascript"><!--
    $('#input-subscription').on('change', function (e) {
        var element = this;

        $('.subscription').addClass('d-none');

        $('#subscription-description-' + $(element).val()).removeClass('d-none');
    });

    $('#form-product').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'index.php?route=checkout/cart|add&language=en-gb',
            type: 'post',
            data: $('#form-product').serialize(),
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded',
            cache: false,
            processData: false,
            beforeSend: function () {
                $('#button-cart').prop('disabled', true).addClass('loading');
            },
            complete: function () {
                $('#button-cart').prop('disabled', false).removeClass('loading');
            },
            success: function (json) {
                $('#form-product').find('.is-invalid').removeClass('is-invalid');
                $('#form-product').find('.invalid-feedback').removeClass('d-block');

                if (json['error']) {
                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#header-cart').load('index.php?route=common/cart|info');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#review').on('click', '.pagination a', function (e) {
        e.preventDefault();

        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/review|review&product_id=42');

    $('#button-review').on('click', function () {
        $.ajax({
            url: 'index.php?route=product/review|write&product_id=42',
            type: 'post',
            dataType: 'json',
            data: $('#form-review').serialize(),
            beforeSend: function () {
                $('#button-review').prop('disabled', true).addClass('loading');
            },
            complete: function () {
                $('#button-review').prop('disabled', false).removeClass('loading');
            },
            success: function (json) {
                $('.alert-dismissible').remove();

                if (json['error']) {
                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#form-review').after('<div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-name').val('');
                    $('#input-text').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                }
            }
        });
    });

    $(document).ready(function () {
        $('.date').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('.time').daterangepicker({
            singleDatePicker: true,
            datePicker: false,
            autoApply: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'HH:mm'
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find('.calendar-table').hide();
        });

        $('.datetime').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm'
            }
        });
    });
    //--></script>-->

<? $popup_js = <<<POPUP_JS
$('.magnific-popup').magnificPopup({
    type: 'image',
    delegate: 'a',
    gallery: {
        enabled: true
    }
});
POPUP_JS;
$this->registerJs($popup_js);
?>