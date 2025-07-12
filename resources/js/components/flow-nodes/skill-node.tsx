import React from 'react';
import { Handle, Position } from 'reactflow';
import { Badge } from '@/components/ui/badge';
import { GitBranch } from 'lucide-react';

interface SkillNodeData {
  id: number;
  name: string;
  description?: string;
  color: string;
  activities_count: number;
}

interface SkillNodeProps {
  data: SkillNodeData;
  isConnectable: boolean;
}

export default function SkillNode({ data, isConnectable }: SkillNodeProps) {
  return (
    <div 
      className="skill-node bg-white border-2 rounded-lg shadow-xl min-w-48 max-w-64 relative"
      style={{ borderColor: data.color }}
    >
      {/* Glow effect */}
      <div 
        className="absolute inset-0 rounded-lg blur-sm -z-10"
        style={{ 
          backgroundColor: data.color + '30',
          filter: 'blur(4px)',
        }}
      />
      
      {/* Header with skill color */}
      <div 
        className="px-4 py-3 rounded-t-lg border-b-2"
        style={{ 
          backgroundColor: data.color + '20', 
          borderColor: data.color 
        }}
      >
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <div 
              className="w-3 h-3 rounded-full"
              style={{ backgroundColor: data.color }}
            />
            <GitBranch className="w-4 h-4" style={{ color: data.color }} />
          </div>
          <Badge variant="secondary" className="text-xs">
            {data.activities_count} activities
          </Badge>
        </div>
      </div>

      {/* Content */}
      <div className="p-4">
        <h3 className="font-semibold text-lg mb-2 text-gray-900">
          {data.name}
        </h3>
        {data.description && (
          <p className="text-sm text-gray-600 mb-3 line-clamp-2">
            {data.description}
          </p>
        )}
        <div className="flex items-center justify-center">
          <div 
            className="text-xs font-medium px-2 py-1 rounded"
            style={{ 
              backgroundColor: data.color + '15',
              color: data.color 
            }}
          >
            SKILL ROOT
          </div>
        </div>
      </div>

      {/* Only output handle since skills are source nodes */}
      <Handle
        type="source"
        position={Position.Bottom}
        isConnectable={isConnectable}
        style={{
          background: data.color,
          border: `2px solid ${data.color}`,
          width: 12,
          height: 12,
        }}
      />
    </div>
  );
} 