<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2019-Jan-22
 */

namespace App\Helpers;


use App\Models\Device;
use Exception;

class Packet
{
    /**
     * @param $buffer
     * @param $buffer_size
     */
    public static function printHexBuffer($buffer, $buffer_size)
    {
        for ($i = 0; $i < $buffer_size; $i++) {
            printf("0x%02x ", $buffer[$i]);
            echo ', index: ' . $i . EOL;
        }

        echo EOL;
    }

    /**
     * Decodes an institution id from a received packet
     *
     * @param array $buffer
     * @return string
     */
    public static function decodeInstitutionId(array $buffer)
    {
        if (!$buffer) return null;

        $region = $buffer[2];
        $type = $buffer[3];
        $code = (($buffer[4] & 0xFF) << 8) | ($buffer[5] & 0xFF);

        return $region . $type . $code;
    }

    /**
     * Creates a session's Id
     *
     * @return string
     */
    public static function sessionCreateId()
    {
        mt_srand();
        return bin2hex(pack('d', microtime(true)) . pack('N', mt_rand(0, 2147483647)));
    }

    /**
     * Merges a packet with a block
     *
     * @param array $packet
     * @param array $block
     */
    public static function mergePacketBlock(array $packet, array $block)
    {
        foreach ($block as $key => $value) {
            $packet[count($packet)] = chr($value);
        }
    }

    /**
     * Creates a packet
     *
     * @param string $data
     * @return array
     */
    public static function createPacket($data)
    {
        $packet = array();
        $packet[count($packet)] = chr(2); //Initialize
        self::mergePacketBlock($packet, unpack('C*', strlen($data)));
        $packet[count($packet)] = chr(4); //Separator
        self::mergePacketBlock($packet, unpack('C*', $data));

        return $packet;
    }

    /**
     * @param $buffer
     * @param $institution_id
     * @param $gateway_id
     * @param $gateway_size
     */
    public static function updateFile($buffer, $institution_id, $gateway_id, $gateway_size)
    {
        $fp = fopen($institution_id . '.csv', 'w');
        for ($i = 0; $i < $gateway_size; $i++) {
            $line = [];
            $offset = $i * 17;
            $erv_id = $buffer[8 + $offset];
            $op_status = $buffer[9 + $offset];
            $occupancy = $buffer[10 + $offset];
            $filter_replacement_cycle = $buffer[11 + $offset];
            $particles = (($buffer[12 + $offset] & 0xFF) << 8) | ($buffer[13 + $offset] & 0xFF);
            $particles_outdoor = (($buffer[14 + $offset] & 0xFF) << 8) | ($buffer[15 + $offset] & 0xFF);
            $dioxide = (($buffer[16 + $offset] & 0xFF) << 8) | ($buffer[17 + $offset] & 0xFF);
            $voc = (($buffer[18 + $offset] & 0xFF) << 8) | ($buffer[19 + $offset] & 0xFF);
            $radon = (($buffer[20 + $offset] & 0xFF) << 8) | ($buffer[21 + $offset] & 0xFF);
            $alarm = $buffer[22 + $offset];

            $device_id = $gateway_id . $erv_id;
            $line[] = $device_id;
            $line[] = $op_status;
            $line[] = $occupancy;
            $line[] = $filter_replacement_cycle;
            $line[] = $particles;
            $line[] = $particles_outdoor;
            $line[] = $dioxide;
            $line[] = $voc;
            $line[] = $radon;
            $line[] = $alarm;

            fputcsv($fp, $line);
        }
        fclose($fp);
    }

    /**
     * @param bool $has_record
     * @param int $counter
     * @param bool $equal
     * @return bool
     */
    public static function hasUpdate($has_record, $counter, $equal)
    {
        return ($counter > 0 && $counter < 20) ? (!$has_record || !$equal) : true;
    }

    /**
     * @param bool $has_update
     * @param Device $device
     * @param Device $old_device
     * @return bool
     */
    public static function hasAlarm($has_update, $device, $old_device)
    {
//    echo '$has_update: ' . ($has_update ? 'true' : 'false') .
//        ' $device->alarm > 0: ' . (($device->alarm > 0) ? 'true' : 'false') .
//        ' $device->alarm != $old_device->alarm: ' . (($device->alarm != $old_device->alarm) ? 'true' : 'false') . EOL;
//    echo '$device->alarm: ' . $device->alarm . ' $old_device->alarm: ' . $old_device->alarm . EOL;

        return $has_update && ($device->alarm > 0) && ($device->alarm != $old_device->alarm);
    }

    /**
     * @param array $buffer
     * @param int $index
     * @param int $gateway_id
     * @param int $institution_no
     *
     * @return Device
     */
    public static function decodeDevice($buffer, $index, $gateway_id, $institution_no)
    {
        if (!$buffer) return null;
        if (!isset($index)) return null;
        if (!isset($gateway_id)) return null;
        if (!isset($institution_no)) return null;

        $record = [];
        $device = new Device();

        try {
            $offset = $index * 17;
            $erv_id = $buffer[8 + $offset];
            $operation = $buffer[9 + $offset];
            $occupancy = $buffer[10 + $offset];
            $filter_replacement_cycle = $buffer[11 + $offset];
            $particles = (($buffer[12 + $offset] & 0xFF) << 8) | ($buffer[13 + $offset] & 0xFF);
            $dust = (($buffer[14 + $offset] & 0xFF) << 8) | ($buffer[15 + $offset] & 0xFF);
            $dioxide = (($buffer[16 + $offset] & 0xFF) << 8) | ($buffer[17 + $offset] & 0xFF);
            $voc = (($buffer[18 + $offset] & 0xFF) << 8) | ($buffer[19 + $offset] & 0xFF);
            $radon = (($buffer[20 + $offset] & 0xFF) << 8) | ($buffer[21 + $offset] & 0xFF);
            $alarm = $buffer[22 + $offset];

            if (isset($institution_no)) $record['institution_no'] = $institution_no;

            $record['gw_id'] = $gateway_id;
            $record['erv_id'] = $erv_id;
            $record['operation'] = $operation;
            $record['occupancy'] = $occupancy;
            $record['filter_replacement_cycle'] = $filter_replacement_cycle;
            $record['particles'] = Indicator::standardize('particles', $particles);
            $record['dust'] = Indicator::standardize('particles', $dust);
            $record['dioxide'] = Indicator::standardize('dioxide', $dioxide);
            $record['voc'] = Indicator::standardize('voc', $voc);
            $record['radon'] = Indicator::standardize('radon', $radon);
            $record['alarm'] = $alarm;

            $device->set($record);

            return $device;
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}