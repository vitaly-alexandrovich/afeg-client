<?php
namespace Afeg;

use Modeler\Model;
use Modeler\Property;

/**
 * @method boolean hasShortTime()
 * @method boolean hasSenderName()
 * @method boolean hasSenderEmail()
 * @method boolean hasTime()
 * @method boolean hasSubject()
 * @method boolean hasText()
 * @method boolean hasHtml()
 * @method boolean hasAttachments()
 * @method string getShortTime()
 * @method string getSenderName()
 * @method string getSenderEmail()
 * @method string getTime()
 * @method string getSubject()
 * @method string getText()
 * @method string getHtml()
 * @method string getAttachments()
 */
class Message extends Model
{
    /**
     * @return array
     */
    protected static function mapProperties()
    {
        return [
            'short_time' => Property::string()->notNull(),
            'sender_name' => Property::string()->notNull(),
            'sender_email' => Property::string()->notNull(),
            'time' => Property::string()->notNull(),
            'subject' => Property::string(),
            'text' => Property::string(),
            'html' => Property::string(),
            'attachments' => Property::raw(),
        ];
    }

    /**
     * @return false|int
     */
    public function getTimestamp()
    {
        return strtotime($this->getTime());
    }

    /**
     * @param string $format
     * @return false|string
     */
    public function getFormattedTime($format = 'd.m.Y H:i:s')
    {
        return date($format, $this->getTimestamp());
    }
}
