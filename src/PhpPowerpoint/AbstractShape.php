<?php
/**
 * This file is part of PHPPowerPoint - A pure PHP library for reading and writing
 * presentations documents.
 *
 * PHPPowerPoint is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPPowerPoint
 * @copyright   2009-2014 PHPPowerPoint contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpPowerpoint;

use PhpOffice\PhpPowerpoint\Shape\Hyperlink;
use PhpOffice\PhpPowerpoint\Style\Fill;
use PhpOffice\PhpPowerpoint\Style\Shadow;

/**
 * Abstract shape
 */
abstract class AbstractShape implements ComparableInterface
{
    /**
     * Container
     *
     * @var \PhpOffice\PhpPowerpoint\ShapeContainerInterface
     */
    protected $container;

    /**
     * Offset X
     *
     * @var int
     */
    protected $offsetX;

    /**
     * Offset Y
     *
     * @var int
     */
    protected $offsetY;

    /**
     * Width
     *
     * @var int
     */
    protected $width;

    /**
     * Height
     *
     * @var int
     */
    protected $height;

    /**
     * Fill
     *
     * @var \PhpOffice\PhpPowerpoint\Style\Fill
     */
    private $fill;

    /**
     * Border
     *
     * @var \PhpOffice\PhpPowerpoint\Style\Border
     */
    private $border;

    /**
     * Rotation
     *
     * @var int
     */
    protected $rotation;

    /**
     * Shadow
     *
     * @var \PhpOffice\PhpPowerpoint\Style\Shadow
     */
    protected $shadow;

    /**
     * Hyperlink
     *
     * @var \PhpOffice\PhpPowerpoint\Shape\Hyperlink
     */
    protected $hyperlink;

    /**
     * Hash index
     *
     * @var string
     */
    private $hashIndex;

    /**
     * Create a new self
     */
    public function __construct()
    {
        // Initialise values
        $this->container = null;
        $this->offsetX   = 0;
        $this->offsetY   = 0;
        $this->width     = 0;
        $this->height    = 0;
        $this->rotation  = 0;
        $this->fill      = new Style\Fill();
        $this->border    = new Style\Border();
        $this->shadow    = new Style\Shadow();

        $this->border->setLineStyle(Style\Border::LINE_NONE);
    }
    
    public function __clone() {
        $this->container = null;
        $this->fill      = clone $this->fill;
        $this->border    = clone $this->border;
        $this->shadow    = clone $this->shadow;
    }

    /**
     * Get Container, Slide or Group
     *
     * @return \PhpOffice\PhpPowerpoint\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set Container, Slide or Group
     *
     * @param  \PhpOffice\PhpPowerpoint\ShapeContainerInterface $pValue
     * @param  bool                $pOverrideOld If a Slide has already been assigned, overwrite it and remove image from old Slide?
     * @throws \Exception
     * @return self
     */
    public function setContainer(ShapeContainerInterface $pValue = null, $pOverrideOld = false)
    {
        if (is_null($this->container)) {
            // Add drawing to \PhpOffice\PhpPowerpoint\ShapeContainerInterface
            $this->container = $pValue;
            if (!is_null($this->container)) {
                $this->container->getShapeCollection()->append($this);
            }
        } else {
            if ($pOverrideOld) {
                // Remove drawing from old \PhpOffice\PhpPowerpoint\ShapeContainerInterface
                $iterator = $this->container->getShapeCollection()->getIterator();

                while ($iterator->valid()) {
                    if ($iterator->current()->getHashCode() == $this->getHashCode()) {
                        $this->container->getShapeCollection()->offsetUnset($iterator->key());
                        $this->container = null;
                        break;
                    }
                   $iterator->next();
                }

                // Set new \PhpOffice\PhpPowerpoint\Slide
                $this->setContainer($pValue);
            } else {
                throw new \Exception("A \PhpOffice\PhpPowerpoint\ShapeContainerInterface has already been assigned. Shapes can only exist on one \PhpOffice\PhpPowerpoint\ShapeContainerInterface.");
            }
        }

        return $this;
    }

    /**
     * Get Slide
     *
     * @return \PhpOffice\PhpPowerpoint\Container
     * @deprecated
     */
    public function getSlide()
    {
        return $this->getContainer();
    }

    /**
     * Set Slide
     *
     * @param  \PhpOffice\PhpPowerpoint\Slide $pValue
     * @param  bool                $pOverrideOld If a Slide has already been assigned, overwrite it and remove image from old Slide?
     * @throws \Exception
     * @return self
     * @deprecated
     */
    public function setSlide(Slide $pValue = null, $pOverrideOld = false)
    {
        return $this->setContainer($pValue, $pOverrideOld);
    }

    /**
     * Get OffsetX
     *
     * @return int
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }

    /**
     * Set OffsetX
     *
     * @param  int                 $pValue
     * @return self
     */
    public function setOffsetX($pValue = 0)
    {
        $this->offsetX = $pValue;

        return $this;
    }

    /**
     * Get OffsetY
     *
     * @return int
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }

    /**
     * Set OffsetY
     *
     * @param  int                 $pValue
     * @return self
     */
    public function setOffsetY($pValue = 0)
    {
        $this->offsetY = $pValue;

        return $this;
    }

    /**
     * Get Width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set Width
     *
     * @param  int                 $pValue
     * @return self
     */
    public function setWidth($pValue = 0)
    {
        $this->width = $pValue;
        return $this;
    }

    /**
     * Get Height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set Height
     *
     * @param  int                 $pValue
     * @return self
     */
    public function setHeight($pValue = 0)
    {
        $this->height = $pValue;
        return $this;
    }

    /**
     * Set width and height with proportional resize
     *
     * @param  int                 $width
     * @param  int                 $height
     * @example $objDrawing->setWidthAndHeight(160,120);
     * @return self
     */
    public function setWidthAndHeight($width = 0, $height = 0)
    {
        $this->width  = $width;
        $this->height = $height;
        return $this;
    }

    /**
     * Get Rotation
     *
     * @return int
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * Set Rotation
     *
     * @param  int                 $pValue
     * @return self
     */
    public function setRotation($pValue = 0)
    {
        $this->rotation = $pValue;
        return $this;
    }

    /**
     * Get Fill
     *
     * @return \PhpOffice\PhpPowerpoint\Style\Fill
     */
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * Set Fill
     * @param \PhpOffice\PhpPowerpoint\Style\Fill $pValue
     * @return \PhpOffice\PhpPowerpoint\AbstractShape
     */
    public function setFill(Fill $pValue = null)
    {
        $this->fill = $pValue;
        return $this;
    }

    /**
     * Get Border
     *
     * @return \PhpOffice\PhpPowerpoint\Style\Border
     */
    public function getBorder()
    {
        return $this->border;
    }

    /**
     * Get Shadow
     *
     * @return \PhpOffice\PhpPowerpoint\Style\Shadow
     */
    public function getShadow()
    {
        return $this->shadow;
    }

    /**
     * Set Shadow
     *
     * @param  \PhpOffice\PhpPowerpoint\Style\Shadow $pValue
     * @throws \Exception
     * @return self
     */
    public function setShadow(Shadow $pValue = null)
    {
        $this->shadow = $pValue;
        return $this;
    }

    /**
     * Has Hyperlink?
     *
     * @return boolean
     */
    public function hasHyperlink()
    {
        return !is_null($this->hyperlink);
    }

    /**
     * Get Hyperlink
     *
     * @return \PhpOffice\PhpPowerpoint\Shape\Hyperlink
     */
    public function getHyperlink()
    {
        if (is_null($this->hyperlink)) {
            $this->hyperlink = new Hyperlink();
        }
        return $this->hyperlink;
    }

    /**
     * Set Hyperlink
     *
     * @param  \PhpOffice\PhpPowerpoint\Shape\Hyperlink $pHyperlink
     * @throws \Exception
     * @return self
     */
    public function setHyperlink(Hyperlink $pHyperlink = null)
    {
        $this->hyperlink = $pHyperlink;
        return $this;
    }

    /**
     * Get hash code
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        return md5((is_object($this->container)?$this->container->getHashCode():'') . $this->offsetX . $this->offsetY . $this->width . $this->height . $this->rotation . $this->getFill()->getHashCode() . $this->shadow->getHashCode() . (is_null($this->hyperlink) ? '' : $this->hyperlink->getHashCode()) . __CLASS__);
    }

    /**
     * Get hash index
     *
     * Note that this index may vary during script execution! Only reliable moment is
     * while doing a write of a workbook and when changes are not allowed.
     *
     * @return string Hash index
     */
    public function getHashIndex()
    {
        return $this->hashIndex;
    }

    /**
     * Set hash index
     *
     * Note that this index may vary during script execution! Only reliable moment is
     * while doing a write of a workbook and when changes are not allowed.
     *
     * @param string $value Hash index
     */
    public function setHashIndex($value)
    {
        $this->hashIndex = $value;
    }
}
